<?php

namespace App\Engine\Player;

use App\Engine\Item\ItemInstantiator;
use App\Engine\Player\Reward\MasteryReward;
use App\GameElement\Gathering\Reward\ItemReward;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Reward\RewardApply;
use App\GameElement\Reward\RewardNotificationInterface;
use App\GameObject\Item\AbstractBaseItemPrototype;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class PlayerRewardApplyEngine
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        private PlayerItemEngine              $playerEngine,
        private NotificationEngine $notificationEngine,
        private ItemInstantiator $instantiator,
    )
    {
    }

    public function __invoke(RewardApply $rewardApplication): void
    {
        $recipe = $rewardApplication->getRecipe();
        if (!$recipe instanceof PlayerToken) {
            return;
        }

        $recipe = $this->repository->find($recipe->getId());

        $reward = $rewardApplication->getReward();
        if ($reward instanceof MasteryReward) {
            $recipe->increaseMasteryExperience($reward->getType(), $reward->getExperience());
            $this->repository->save($recipe);
        }

        if ($reward instanceof ItemReward) {
            try {
                /** @var AbstractBaseItemPrototype $item */
                $item = $reward->getItem();
                $this->playerEngine->giveItem($recipe, $this->instantiator->createFrom($item, $reward->getQuantity()));
            } catch (MaxBagSizeReachedException $e) {
                $this->notificationEngine->danger($recipe->getId(), 'Your bag is full, you cannot receive the item.');
                return;
            }
        }

        if ($reward instanceof RewardNotificationInterface) {
            $this->notificationEngine->success($recipe->getId(), sprintf('+%s %s', $reward->getQuantity(), $reward->getName()));
        }
    }
}