<?php

namespace App\Engine\Reward;

use App\Engine\Player\PlayerEngine;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\GameElement\Reward\RewardNotificationInterface;
use App\GameElement\Reward\RewardPlayer;
use App\GameObject\Reward\MasteryReward;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class RewardPlayerHandler
{
    public function __construct(
        private PlayerCharacterRepository $repository,
        private PlayerEngine              $playerEngine,
        private NotificationEngine $notificationEngine,
    )
    {
    }

    public function __invoke(RewardPlayer $rewardPlayer): void
    {
        $playerCharacter = $this->repository->find($rewardPlayer->getPlayerId());
        if (!$playerCharacter instanceof PlayerCharacter) {
            //TODO: scrivere un log o eseguire qualcosa
            return;
        }

        $reward = $rewardPlayer->getReward();
        if ($reward instanceof MasteryReward) {
            $playerCharacter->increaseMasteryExperience($reward->getType(), $reward->getExperience());
        }

       if ($reward instanceof ItemReward) {
           try {
               $this->playerEngine->giveItem($playerCharacter, ItemInstance::createFrom($reward->getItem(), $reward->getQuantity()));
           } catch (MaxBagSizeReachedException $e) {
               throw new UserNotificationException($playerCharacter->getId(), 'Your bag is full, you cannot receive the item.', $e);
           }
       }

        if ($reward instanceof RewardNotificationInterface) {
            $this->notificationEngine->success($rewardPlayer->getPlayerId(), sprintf('+%s %s', $reward->getQuantity(), $reward->getName()));
        }

        $this->repository->save($playerCharacter);
    }
}