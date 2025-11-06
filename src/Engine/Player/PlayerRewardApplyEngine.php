<?php

namespace App\Engine\Player;

use App\Engine\Reward\MasteryReward;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\GameObject;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Gathering\Reward\ItemReward;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Mastery\Engine\MasteryTypeRepository;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Render\Component\RenderComponent;
use App\GameElement\Reward\RewardApplierInterface;
use App\GameElement\Reward\RewardApply;
use App\Repository\Data\PlayerCharacterRepository;

readonly class PlayerRewardApplyEngine implements RewardApplierInterface
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        private PlayerItemEngine          $playerEngine,
        private NotificationEngine        $notificationEngine,
        private MasteryTypeRepository     $masteryEngine,
        protected GameObjectEngine        $gameObjectEngine,
    )
    {
    }

    public function supports(RewardApply $rewardApply): bool
    {
        return $rewardApply->getRecipe() instanceof PlayerCharacter;
    }

    public function apply(RewardApply $rewardApply): void
    {
        /** @var PlayerCharacter $player */
        $player = $rewardApply->getRecipe();

        $reward = $rewardApply->getReward();
        if ($reward instanceof MasteryReward) {
            $player->increaseMasteryExperience($reward->getMasteryId(), $reward->getExperience());
            $this->repository->save($player);
            $this->notificationEngine->success($player->getId(), sprintf('<span class="fas fa-dumbbell"></span> +%s experience on %s', $reward->getQuantity(), $this->masteryEngine->get($reward->getMasteryId())::getName()));
        }

        if ($reward instanceof ItemReward) {
            try {
                $itemPrototype = $this->gameObjectEngine->getPrototype($reward->getItemPrototypeId());
                $item = new GameObject($itemPrototype, $itemPrototype->getComponents());
                $item->setComponent(StackComponent::class, new StackComponent($reward->getQuantity()));
                $this->playerEngine->giveItem($player, $item);
                $this->notificationEngine->success($player->getId(), sprintf('<span class="fas fa-gift"></span> +%d %s', $item->getComponent(StackComponent::class)->getCurrentQuantity(), $item->getComponent(RenderComponent::class)->getName()));
            } catch (MaxBagSizeReachedException) {
                $this->notificationEngine->danger($player->getId(), 'Your bag is full, you cannot receive the item.');
                return;
            }
        }

    }
}