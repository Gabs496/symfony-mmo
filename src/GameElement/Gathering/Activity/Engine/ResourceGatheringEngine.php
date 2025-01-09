<?php

namespace App\GameElement\Gathering\Activity\Engine;

use App\Core\Engine;
use App\Engine\Reward\PlayerRewardEngine;
use App\Entity\ActivityStep;
use App\Entity\Data\Activity;
use App\Entity\Data\MapAvailableActivity;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\ActivityInvolvableInterface;
use App\GameElement\Activity\Engine\AbstractActivityEngine;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Engine\ResourceCollection;
use App\GameElement\Item\Reward\ItemReward;
use App\GameObject\Activity\ActivityType;
use App\GameObject\Reward\MasteryReward;
use App\GameTask\Message\BroadcastActivityStatusChange;
use App\GameTask\Message\ConsumeMapAvailableActivity;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AutoconfigureTag('game.engine.action')]
#[Engine(ResourceGatheringActivity::class)]
readonly class ResourceGatheringEngine extends AbstractActivityEngine
{
    public function __construct(
        private ResourceCollection  $resourceCollection,
        private ActivityRepository  $activityRepository,
        private MessageBusInterface $messageBus,
        private PlayerRewardEngine  $playerRewardEngine,
    )
    {
    }

    /**
     * @psalm-param PlayerCharacter $subject
     * @psalm-param  MapAvailableActivity $directObject
     * @throws Exception|ExceptionInterface
     */
    public function run(object $subject, object $directObject): void
    {
        $activity = (new Activity(ActivityType::RESOURCE_GATHERING));

        try {
            foreach ($this->generateSteps($subject, $directObject) as $generatedStep) {
                $activity->addStep($generatedStep);
            }

            $activity->applyMasteryPerformance($subject->getMasterySet());
            if ($subject instanceof ActivityInvolvableInterface) {
                $subject->startActivity($activity);
            }
            if ($directObject instanceof ActivityInvolvableInterface) {
                $directObject->startActivity($activity);
            }

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

                $this->onStepFinish($subject, $directObject, $step);

//            $step->setIsCompleted(true);
//            $this->repository->save($activity);

                $activity->progressStep();
                $this->activityRepository->save($activity);
            }

            if ($subject instanceof ActivityInvolvableInterface) {
                $subject->endActivity($activity);
            }
            if ($directObject instanceof ActivityInvolvableInterface) {
                $directObject->endActivity($activity);
            }

            $this->activityRepository->remove($activity);
        } catch (Exception $e)
        {
            if ($subject instanceof ActivityInvolvableInterface) {
                $subject->endActivity($activity);
            }
            if ($directObject instanceof ActivityInvolvableInterface) {
                $directObject->endActivity($activity);
            }
            $this->activityRepository->remove($activity);

            throw $e;
        }


    }

    public static function getId(): string
    {
        return self::class;
    }

    /**
     * @psalm-param PlayerCharacter $subject
     * @psalm-param  MapAvailableActivity $directObject
     */
    public function generateSteps(object $subject, object $directObject): iterable
    {
        $resource = $this->resourceCollection->get($directObject->getMapResource()->getResourceId());
        for ($i = 0; $directObject->getQuantity() > $i; $i++) {
            $step = new ActivityStep($resource->getGatheringTime());
            yield $step;
        }
    }

    /**
     * @psalm-param PlayerCharacter $subject
     * @psalm-param  MapAvailableActivity $directObject
     * @throws Throwable
     */
    public function onStepFinish(object $subject, object $directObject, ActivityStep $step): void
    {
        $resource = $this->resourceCollection->get($directObject->getMapResource()->getResourceId());
        $rewards = [
            new MasteryReward($resource->getInvolvedMastery(), 0.01),
            new ItemReward( $resource->getRewardItem(), 1),
        ];
        $this->playerRewardEngine->reward($subject->getId(), $rewards);

        $this->messageBus->dispatch(new ConsumeMapAvailableActivity($directObject->getId()));
    }
}