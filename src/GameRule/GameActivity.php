<?php

namespace App\GameRule;

use App\Entity\ActivityStep;
use App\Entity\ActivityType;
use App\Entity\Data\Activity;
use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerAbstractCharacter;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Mastery;
use App\GameTask\Message\ConsumeMapAvailableActivity;
use App\GameTask\Message\RewardItem;
use App\GameTask\Message\RewardMastery;
use App\GameTask\Message\StartActivity;
use App\Repository\Data\ActivityRepository;
use Exception;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class GameActivity
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ActivityRepository $repository,
    )
    {

    }

    /**
     * @throws ExceptionInterface
     */
    public function startPlayerActivity(PlayerCharacter $playerCharacter, Activity $activity): void
    {
        //TODO: lock player activity
        $this->repository->save($activity);
        $this->messageBus->dispatch(new StartActivity($activity->getId()));
    }

    public function createFromMapAvailableActivity(PlayerCharacter $character, MapAvailableActivity $availableActivity): Activity
    {
        $activity = null;
        if ($availableActivity->getType() === ActivityType::RESOURCE_GATHERING) {

            $resource = $availableActivity->getMapResource()->getResource();
            $activity = (new Activity(ActivityType::RESOURCE_GATHERING))
                ->setMasteryInvolveds([new Mastery($resource->getMasteryInvolved(), $resource->getDifficulty())])
            ;

            for ($i = 0; $availableActivity->getQuantity() > $i; $i++) {
                $step = (new ActivityStep($resource->getGatheringTime()))
                    ->addOnFinish(new RewardMastery($character->getId(), $resource->getMasteryInvolved(), 0.01))
                    ->addOnFinish(new RewardItem($character->getId(), $resource->getProduct()->getId(), 1))
                    ->addOnFinish(new ConsumeMapAvailableActivity($availableActivity->getId()));
                ;

                $activity->addStep($step);
            }
        }

        if ($activity === null) {
            throw new Exception('Activity type not implemented');
        }

        $activity->applyMasteryPerformance($character->getMasterySet());

        return $activity;
    }
}