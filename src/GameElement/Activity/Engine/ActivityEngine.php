<?php

namespace App\GameElement\Activity\Engine;

use App\Entity\Data\Activity;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Activity\Event\ActivityTimeoutEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Activity\Exception\ActivityDurationNotSetException;
use App\GameElement\Activity\Message\ActivityTimeout;
use App\GameElement\Core\Token\TokenEngine;
use App\Repository\Data\ActivityRepository;
use ReflectionClass;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler(handles: ActivityTimeout::class, method: 'activityTimeout')]
readonly class ActivityEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private ActivityRepository $activityRepository,
        private MessageBusInterface $messageBus,
        protected TokenEngine $tokenEngine,
    )
    {
    }

    public function run(AbstractActivity $activity): void
    {
        $this->eventDispatcher->dispatch(new BeforeActivityStartEvent($activity));

        if ($activity->getDuration() === null) {
            throw new ActivityDurationNotSetException($activity);
        }

        $activityEntity = (new Activity(ActivityEngine::getId($activity), $activity->getDuration()));
        $this->activityRepository->save($activityEntity);
        $activity->setEntityId($activityEntity->getId());
        $activity->start();

        $this->eventDispatcher->dispatch(new ActivityStartEvent($activity));

        $activityEntity->setStartedAt(microtime(true));
        $this->activityRepository->save($activityEntity);

        $activity->setSubject(null);
        $this->messageBus->dispatch(new ActivityTimeout($activity),[new DelayStamp($this->getMillisecondsDuration($activity))]);
    }

    protected function getMillisecondsDuration(AbstractActivity $activity): int
    {
        $seconds = $activity->getDuration();
        return (int)bcmul($seconds, 1000, 4);
    }

    public static function getId(AbstractActivity $activity)
    {
        $reflectionClass = new ReflectionClass($activity);
        //TODO: cache it (Need to modify Activity class)
        foreach ($reflectionClass->getAttributes(\App\GameElement\Activity\Activity::class) as $attribute) {
            return $attribute->getArguments()['id'];
        }

        return $activity::class;
    }

    public function activityTimeout(ActivityTimeout $message): void
    {
        $activity = $message->getActivity();
        $activity->setSubject($this->tokenEngine->exchange($activity->getSubjectToken()));
        $activityEntity = $this->activityRepository->find($activity->getEntityId());
        if (!$activityEntity instanceof Activity) {
            return;
        }

        $timeoutEvent = new ActivityTimeoutEvent($message->getActivity());
        $this->eventDispatcher->dispatch($timeoutEvent);

        $activityEntity->setCompletedAt(microtime(true));
        $this->activityRepository->save($activityEntity);

        $this->eventDispatcher->dispatch(new ActivityEndEvent($activity));
    }
}