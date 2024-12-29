<?php

namespace App\GameRule;

use App\Entity\ActivityStep;
use App\Entity\ActivityType;
use App\Entity\Data\Activity;
use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\Entity\Mastery;
use App\GameTask\Message\BroadcastActivityStatusChange;
use App\GameTask\Message\ConsumeMapAvailableActivity;
use App\GameTask\Message\RewardItem;
use App\GameTask\Message\RewardMastery;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Twig\Environment;

readonly class GameActivity
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ActivityRepository $repository,
        private HubInterface $hub,
        private Environment $twig,
        private ResourceCollection $resourceCollection,
    )
    {

    }

    /**
     * @throws ExceptionInterface
     */
    public function startPlayerActivity(PlayerCharacter $playerCharacter, Activity $activity): void
    {
        //TODO: lock player activity

        $activity->setStartedAt(new DateTimeImmutable());
        $this->repository->save($activity);

        while ($step = $activity->getNextStep()) {
            $step->setScheduledAt(microtime(true));
            $this->repository->save($activity);
            $this->messageBus->dispatch(new BroadcastActivityStatusChange($activity->getId()));

            $this->waitForStepFinish($step);

            $activity = $this->repository->find($activity->getId());
            if (!$activity instanceof Activity) {
                return;
            }

//            $step->setIsCompleted(true);
//            $this->repository->save($activity);

            foreach ($step->getOnFinish() as $onFinish) {
                $this->messageBus->dispatch($onFinish);
            }
            $activity->progressStep();
            $this->repository->save($activity);
        }

        $this->repository->remove($activity);
    }

    public function createFromMapAvailableActivity(PlayerCharacter $character, MapAvailableActivity $availableActivity): Activity
    {
        $activity = null;
        if ($availableActivity->getType() === ActivityType::RESOURCE_GATHERING) {

            $resource = $this->resourceCollection->getResource($availableActivity->getMapResource()->getResourceId());
            $activity = (new Activity(ActivityType::RESOURCE_GATHERING))
                ->setMasteryInvolveds([new Mastery($resource->getInvolvedMastery(), $resource->getDifficulty())])
            ;

            for ($i = 0; $availableActivity->getQuantity() > $i; $i++) {
                $step = (new ActivityStep($resource->getGatheringTime()))
                    ->addOnFinish(new RewardMastery($character->getId(), $resource->getInvolvedMastery(), 0.01))
                    ->addOnFinish(new RewardItem($character->getId(), $resource->getRewardItemId(), 1))
                    ->addOnFinish(new ConsumeMapAvailableActivity($availableActivity->getId()))
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

    private function waitForStepFinish(ActivityStep $step): void
    {
        $seconds = floor($step->getDuration());
        $microseconds = (int)bcmul(bcsub($step->getDuration(), $seconds, 4), 1000000, 0);
        sleep($seconds);
        usleep($microseconds);
    }
}