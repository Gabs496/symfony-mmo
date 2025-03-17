<?php

namespace App\GameElement\Activity\Engine;

use App\Entity\Data\Activity;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Activity\Exception\ActivityDurationNotSetException;
use App\Repository\Data\ActivityRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use ReflectionClass;

readonly class ActivityEngine
{

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private ActivityRepository $activityRepository,
    )
    {
    }

    public function run(object $subject, AbstractActivity $type): void
    {
        $this->eventDispatcher->dispatch(new BeforeActivityStartEvent($type, $subject));

        if ($type->getDuration() === null) {
            throw new ActivityDurationNotSetException($type);
        }

        $activityEntity = (new Activity(ActivityEngine::getId($type), $type->getDuration()));
        $this->activityRepository->save($activityEntity);
        $type->setEntity($activityEntity);
        $type->start();

        $this->eventDispatcher->dispatch(new ActivityStartEvent($type, $subject));

        $activityEntity->setStartedAt(microtime(true));
        $this->activityRepository->save($activityEntity);

        $this->waitForActivityFinish($type);

        $activityEntity = $this->activityRepository->find($activityEntity->getId());
        if (!$activityEntity instanceof Activity) {
            return;
        }

        $activityEntity->setCompletedAt(microtime(true));
        $this->activityRepository->save($activityEntity);

        $this->eventDispatcher->dispatch(new ActivityEndEvent($type, $subject));
    }

    protected function waitForActivityFinish(AbstractActivity $activity): void
    {
        $seconds = floor($activity->getDuration());
        $microseconds = (int)bcmul(bcsub($activity->getDuration(), $seconds, 4), 1000000, 0);
        sleep($seconds);
        usleep($microseconds);
    }

    public static function getId(AbstractActivity $activity)
    {
        $reflectionClass = new ReflectionClass($activity);
        foreach ($reflectionClass->getAttributes(\App\GameElement\Activity\Activity::class) as $attribute) {
            return $attribute->getArguments()['id'];
        }

        return $activity::class;
    }
}