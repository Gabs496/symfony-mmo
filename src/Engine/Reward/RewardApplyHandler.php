<?php

namespace App\Engine\Reward;

use App\Engine\Player\PlayerEngine;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Mastery\MasteryReward;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Reward\RewardApply;
use App\GameElement\Reward\RewardNotificationInterface;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class RewardApplyHandler
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        private PlayerEngine              $playerEngine,
        private NotificationEngine $notificationEngine,
    )
    {
    }

    public function __invoke(RewardApply $rewardApplication): void
    {
        $recipe = $rewardApplication->getRecipe();
        if ($recipe instanceof PlayerCharacter) {
            $playerCharacter = $this->repository->find($recipe->getId());

            if (!$playerCharacter instanceof PlayerCharacter) {
                //TODO: scrivere un log o eseguire qualcosa
                return;
            }

            $reward = $rewardApplication->getReward();
            if ($reward instanceof MasteryReward) {
                $playerCharacter->increaseMasteryExperience($reward->getType(), $reward->getExperience());
            }

            if ($reward instanceof ItemReward) {
                try {
                    $this->playerEngine->giveItem($playerCharacter, ItemInstance::createFrom($reward->getItem(), $reward->getQuantity()));
                } catch (MaxBagSizeReachedException $e) {
                    $this->notificationEngine->danger($playerCharacter->getId(), 'Your bag is full, you cannot receive the item.');
                    return;
                }
            }

            $this->repository->save($playerCharacter);

            if ($reward instanceof RewardNotificationInterface) {
                $this->notificationEngine->success($playerCharacter->getId(), sprintf('+%s %s', $reward->getQuantity(), $reward->getName()));
            }
        }
    }
}