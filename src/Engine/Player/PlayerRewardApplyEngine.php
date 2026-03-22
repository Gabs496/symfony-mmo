<?php

namespace App\Engine\Player;

use App\Engine\Math;
use App\Engine\Reward\MasteryReward;
use App\Entity\Data\Player;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Reward\CombatStatReward;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Item\Reward\ItemRuntimeCreatedReward;
use App\GameElement\Mastery\Engine\MasteryTypeRepository;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\GameElement\Render\Component\RenderComponent;
use App\GameElement\Reward\RewardApplierInterface;
use App\GameElement\Reward\RewardApply;
use App\Repository\Data\PlayerCharacterRepository;
use App\Stream\PlayerStatsStream;
use App\Stream\Streamer;
use Doctrine\ORM\EntityManagerInterface;
use PennyPHP\Core\Engine\GameObjectEngine;

readonly class PlayerRewardApplyEngine implements RewardApplierInterface
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        private NotificationEngine        $notificationEngine,
        private MasteryTypeRepository     $masteryEngine,
        private GameObjectEngine          $gameObjectEngine,
        private PlayerCharacterRepository $playerCharacterRepository,
        private PlayerItemEngine          $playerItemEngine,
        private EntityManagerInterface    $entityManager,
        private Streamer $streamer,
    )
    {
    }

    public function supports(RewardApply $rewardApply): bool
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $rewardApply->getRecipe()]);
        return $player instanceof Player;
    }

    public function apply(RewardApply $rewardApply): void
    {
        /** @var Player $player */
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $rewardApply->getRecipe()]);

        $reward = $rewardApply->getReward();
        if ($reward instanceof MasteryReward) {
            $player->increaseMasteryExperience($reward->getMasteryId(), $reward->getExperience());
            $this->repository->save($player);
            $this->notificationEngine->success($player, sprintf('<span class="fas fa-dumbbell"></span> +%s experience on %s', $reward->getQuantity(), $this->masteryEngine->get($reward->getMasteryId())::getName()));
            $this->streamer->send(new PlayerStatsStream($player));
        }

        if ($reward instanceof CombatStatReward) {
            $stat = $player->getGameObject()->getComponent(CombatComponent::class)
                ->getStatByClass($reward->getStatClass())
            ;
            $stat->increase($reward->getAmount());
            $this->repository->save($player);
            $this->notificationEngine->success($player, sprintf('<span class="fas fa-arrow-up"></span> +%d %s', Math::getStatViewValue($reward->getAmount()), ucfirst($stat::getLabel())));
            $this->streamer->send(new PlayerStatsStream($player));
        }

        if ($reward instanceof ItemRuntimeCreatedReward || $reward instanceof ItemReward) {
            if ($reward instanceof ItemRuntimeCreatedReward) {
                $item = $this->gameObjectEngine->make($reward->getItemPrototypeId());
            } else {
                $item = $reward->getItem();
            }
            $this->entityManager->persist($item);

            try {
                $this->playerItemEngine->give($player->getGameObject(), $item, $reward->getQuantity());
            } catch (MaxBagSizeReachedException) {
                throw new UserNotificationException($player->getId(), 'Your bag is full, you cannot receive the item.');
            }

            $this->entityManager->flush();
            $this->notificationEngine->success($player, sprintf('<span class="fas fa-gift"></span> +%d %s', $reward->getQuantity(), $item->getComponent(RenderComponent::class)->getName()));
        }

    }
}