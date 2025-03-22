<?php

namespace App\Engine\Player\Reward;

use App\Engine\Player\Item\PlayerItemEngine;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Mastery\MasteryReward;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Reward\RewardApply;
use App\GameElement\Reward\RewardNotificationInterface;
use App\GameObject\Item\AbstractBaseItem;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class RewardApplyHandler
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        //TODO: remove from this domain
        private PlayerItemEngine              $playerEngine,
        private NotificationEngine $notificationEngine,
    )
    {
    }

    public function __invoke(RewardApply $rewardApplication): void
    {
        $recipe = $rewardApplication->getRecipe();
        if (!$recipe instanceof PlayerCharacter) {
            return;
        }

        $playerCharacter = $this->repository->find($recipe->getId());
        if (!$playerCharacter instanceof PlayerCharacter) {
            //TODO: write log or execute something
            return;
        }

        $reward = $rewardApplication->getReward();
        if ($reward instanceof MasteryReward) {
            $playerCharacter->increaseMasteryExperience($reward->getType(), $reward->getExperience());
        }

        if ($reward instanceof ItemReward) {
            try {
                /** @var AbstractBaseItem $item */
                $item = $reward->getItem();
                $this->playerEngine->giveItem($playerCharacter, $item->createInstance());
            } catch (MaxBagSizeReachedException $e) {
                $this->notificationEngine->danger($playerCharacter->getId(), 'Your bag is full, you cannot receive the item.');
                return;
            }
        }

        if ($reward instanceof RewardNotificationInterface) {
            $this->notificationEngine->success($playerCharacter->getId(), sprintf('+%s %s', $reward->getQuantity(), $reward->getName()));
        }
    }
}