<?php

namespace App\GameElement\Activity\Engine;

use App\Entity\Data\Activity;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Activity\ActivityInvolvableInterface;
use App\GameElement\Activity\ActivityStep;
use App\GameElement\Activity\ActivityWithRewardInterface;
use App\GameElement\Activity\Event\ActivityStepEndEvent;
use App\GameElement\Activity\Event\ActivityStepStartEvent;
use App\GameElement\Reward\RewardApply;
use App\Repository\Data\ActivityRepository;
use DateMalformedStringException;
use DateTimeImmutable;
use Exception;
use ReflectionClass;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @template T
 * @template S
 */
//TODO: manage cancel operation
readonly abstract class AbstractActivityEngine
{
    public function __construct(
        protected ActivityRepository  $activityRepository,
        protected MessageBusInterface $messageBus,
        protected EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    /**
     * @psalm-param T $subject
     * @psalm-param S $directObject
     * @throws ExceptionInterface|DateMalformedStringException
     */
    public function run(object $subject, object $directObject, ActivityInterface $type): void
    {
        $activity = (new Activity($this->getId($type)));
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

            $activity->setStartedAt(new DateTimeImmutable());
            $this->activityRepository->save($activity);

            while ($step = $activity->getNextStep()) {
                $step->setScheduledAt(microtime(true));
                $this->activityRepository->save($activity);

                $this->eventDispatcher->dispatch(new ActivityStepStartEvent($type, $subject));

                $this->waitForStepFinish($step);

                $this->eventDispatcher->dispatch(new ActivityStepEndEvent($type, $subject));

                $activity = $this->activityRepository->find($activity->getId());
                if (!$activity instanceof Activity) {
                    return;
                }

                if ($type instanceof ActivityWithRewardInterface) {
                    foreach ($type->getRewards() as $reward) {
                        $this->messageBus->dispatch(new RewardApply($reward, $subject));
                    }
                }

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

    /**
     * @psalm-param T $subject
     * @psalm-param S $directObject
     * @return ActivityStep[]
     */
    protected abstract function generateSteps(object $subject, object $directObject): iterable;

    protected function waitForStepFinish(ActivityStep $step): void
    {
        $seconds = floor($step->getDuration());
        $microseconds = (int)bcmul(bcsub($step->getDuration(), $seconds, 4), 1000000, 0);
        sleep($seconds);
        usleep($microseconds);
    }

    private function getId(ActivityInterface $activity)
    {
        $reflectionClass = new ReflectionClass($activity);
        foreach ($reflectionClass->getAttributes(\App\GameElement\Activity\Activity::class) as $attribute) {
            return $attribute->getArguments()['id'];
        }

        return $activity::class;
    }
}