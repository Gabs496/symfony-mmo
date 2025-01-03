<?php

namespace App\GameEngine\Gathering\Activity;

use App\Entity\ActivityStep;
use App\Entity\Data\Activity;
use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\Reward\RewardPlayer;
use App\GameEngine\Activity\AbstractActivityEngine;
use App\GameEngine\Engine;
use App\GameEngine\Resource\ResourceCollection;
use App\GameObject\Activity\ActivityType;
use App\GameObject\Activity\ResourceGatheringActivity;
use App\GameObject\Reward\ItemReward;
use App\GameObject\Reward\MasteryReward;
use App\GameTask\Message\BroadcastActivityStatusChange;
use App\GameTask\Message\ConsumeMapAvailableActivity;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AutoconfigureTag('game.engine.action')]
#[Engine(ResourceGatheringActivity::class)]
readonly class ResourceGatheringEngine extends AbstractActivityEngine
{
    public function __construct(
        private ResourceCollection  $resourceCollection,
        private ActivityRepository  $activityRepository,
        private MessageBusInterface $messageBus,
    )
    {
    }

    /**
     * @psalm-param PlayerCharacter[] $who
     * @psalm-param  MapAvailableActivity $directObject
     * @throws Exception|ExceptionInterface
     */
    public function run(object $subject, object $directObject): void
    {
        $character = $subject;

        /** @var AbstractResource $resource */
        $resource = $this->resourceCollection->get($directObject->getMapResource()->getResourceId());
        $activity = (new Activity(ActivityType::RESOURCE_GATHERING));

        for ($i = 0; $directObject->getQuantity() > $i; $i++) {
            $step = new ActivityStep($resource->getGatheringTime());
            $activity->addStep($step);
        }

        $activity->applyMasteryPerformance($character->getMasterySet());
        $directObject->setInvolvingActivity($activity);

        //TODO: lock player activity

        $activity->setStartedAt(new DateTimeImmutable());
        $this->activityRepository->save($activity);

        while ($step = $activity->getNextStep()) {
            $step->setScheduledAt(microtime(true));
            $this->activityRepository->save($activity);
            $this->messageBus->dispatch(new BroadcastActivityStatusChange($activity->getId()));

            $this->waitForStepFinish($step);

            $activity = $this->activityRepository->find($activity->getId());
            if (!$activity instanceof Activity) {
                return;
            }

//            $step->setIsCompleted(true);
//            $this->repository->save($activity);


            $this->messageBus->dispatch(new RewardPlayer($character->getId(), new MasteryReward($resource->getInvolvedMastery(), 0.01)));
            $this->messageBus->dispatch(new RewardPlayer($character->getId(), new ItemReward( $resource->getRewardItemId(), 1)));
            $this->messageBus->dispatch(new ConsumeMapAvailableActivity($directObject->getId()));

            $activity->progressStep();
            $this->activityRepository->save($activity);
        }

        $this->activityRepository->remove($activity);
    }

    private function waitForStepFinish(ActivityStep $step): void
    {
        $seconds = floor($step->getDuration());
        $microseconds = (int)bcmul(bcsub($step->getDuration(), $seconds, 4), 1000000, 0);
        sleep($seconds);
        usleep($microseconds);
    }

    public static function getId(): string
    {
        return self::class;
    }
}