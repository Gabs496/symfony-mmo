<?php

namespace App\GameElement\Activity\Engine;

use App\Entity\Data\Activity;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Activity\ActivityStep;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Activity\Event\ActivityStepEndEvent;
use App\GameElement\Activity\Event\ActivityStepStartEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Activity\Event\BeforeActivityStepStartEvent;
use App\Repository\Data\ActivityRepository;
use DateTimeImmutable;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use ReflectionClass;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

readonly class ActivityEngine
{

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private ActivityRepository $activityRepository,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     * @throws \DateMalformedStringException
     */
    public function run(object $subject, ActivityInterface $type): void
    {
        $activity = (new Activity($this->getId($type)));
        try {

            $this->eventDispatcher->dispatch(new BeforeActivityStartEvent($type, $subject, $activity));

            $activity->applyMasteryPerformance($subject->getMasterySet());

            $activity->setStartedAt(new DateTimeImmutable());
            $this->activityRepository->save($activity);

            $this->eventDispatcher->dispatch(new ActivityStartEvent($type, $subject, $activity));

            while ($step = $activity->getNextStep()) {

                $this->eventDispatcher->dispatch(new BeforeActivityStepStartEvent($type, $subject));

                $step->setScheduledAt(microtime(true));
                $this->activityRepository->save($activity);

                $this->eventDispatcher->dispatch(new ActivityStepStartEvent($type, $subject));

                $this->waitForStepFinish($step);

                $activity = $this->activityRepository->find($activity->getId());
                if (!$activity instanceof Activity) {
                    return;
                }

                $this->eventDispatcher->dispatch(new ActivityStepEndEvent($type, $subject));

                //            $step->setIsCompleted(true);
                //            $this->repository->save($activity);

                $activity->progressStep();
                $this->activityRepository->save($activity);
            }


        } catch (Exception $e)
        {
        }

        $this->eventDispatcher->dispatch(new ActivityEndEvent($type, $subject, $activity));

        $this->activityRepository->remove($activity);

        if (isset($e) && $e) {
            throw $e;
        }
    }

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