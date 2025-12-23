<?php

namespace App\Engine\Player;

use App\Engine\Reward\MasteryReward;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Reward\CombatStatReward;
use App\GameElement\Core\GameObject\Engine\GameObjectEngine;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Item\Reward\ItemRuntimeCreatedReward;
use App\GameElement\Mastery\Engine\MasteryTypeRepository;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Render\Component\RenderComponent;
use App\GameElement\Reward\RewardApplierInterface;
use App\GameElement\Reward\RewardApply;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

readonly class PlayerRewardApplyEngine implements RewardApplierInterface
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        private PlayerItemEngine          $playerEngine,
        private NotificationEngine        $notificationEngine,
        private MasteryTypeRepository     $masteryEngine,
        private GameObjectEngine          $gameObjectEngine,
        private HubInterface              $hub,
        private Environment               $twig,
        private PlayerCharacterRepository   $playerCharacterRepository,
    )
    {
    }

    public function supports(RewardApply $rewardApply): bool
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $rewardApply->getRecipe()]);
        return $player instanceof PlayerCharacter;
    }

    public function apply(RewardApply $rewardApply): void
    {
        /** @var PlayerCharacter $player */
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $rewardApply->getRecipe()]);

        $reward = $rewardApply->getReward();
        if ($reward instanceof MasteryReward) {
            $player->increaseMasteryExperience($reward->getMasteryId(), $reward->getExperience());
            $this->repository->save($player);
            $this->notificationEngine->success($player->getId(), sprintf('<span class="fas fa-dumbbell"></span> +%s experience on %s', $reward->getQuantity(), $this->masteryEngine->get($reward->getMasteryId())::getName()));
            $this->hub->publish(new Update(
                'player_gui_' . $player->getId(),
                $this->twig->render('player_character/stats.stream.html.twig', ['playerCharacter' => $player]),
                true
            ));
        }

        if ($reward instanceof CombatStatReward) {
            $stat = $player->getGameObject()->getComponent(CombatComponent::class)
                ->getStatByClass($reward->getStatClass())
            ;
            $stat->increase($reward->getAmount());
            $this->repository->save($player);
            $this->notificationEngine->success($player->getId(), sprintf('<span class="fas fa-arrow-up"></span> +%d %s', $reward->getAmount(), ucfirst($stat::getLabel())));
            $this->hub->publish(new Update(
                'player_gui_' . $player->getId(),
                $this->twig->render('player_character/stats.stream.html.twig', ['playerCharacter' => $player]),
                true
            ));
        }

        if ($reward instanceof ItemRuntimeCreatedReward || $reward instanceof ItemReward) {
            if ($reward instanceof ItemRuntimeCreatedReward) {
                $itemPrototype = $this->gameObjectEngine->getPrototype($reward->getItemPrototypeId());
                $item = $itemPrototype->make();
                $itemComponent = $item->getComponent(ItemComponent::class);
                $itemComponent->setQuantity($reward->getQuantity());
            } else {
                $item = $reward->getItem();
            }

            try {
                $this->playerEngine->giveItem($player, $item);
                $this->notificationEngine->success($player->getId(), sprintf('<span class="fas fa-gift"></span> +%d %s', $item->getComponent(ItemComponent::class)?->getOrginalAvailability(), $item->getComponent(RenderComponent::class)?->getName()));
            } catch (MaxBagSizeReachedException) {
                $this->notificationEngine->danger($player->getId(), 'Your bag is full, you cannot receive the item.');
                return;
            }
        }

    }
}