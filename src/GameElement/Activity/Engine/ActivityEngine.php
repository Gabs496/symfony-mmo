<?php

namespace App\GameElement\Activity\Engine;

use App\Entity\Data\Activity;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\ActivitySubjectInterface;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Activity\Exception\ActivityDurationNotSetException;
use App\GameElement\Activity\Message\ActivityTimeout;
use App\Repository\Data\ActivityRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use ReflectionClass;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

readonly class ActivityEngine
{

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private ActivityRepository $activityRepository,
        private MessageBusInterface $messageBus,
    )
    {
    }

    public function run(ActivitySubjectInterface $subject, AbstractActivity $type): void
    {
        $this->eventDispatcher->dispatch(new BeforeActivityStartEvent($type, $subject));

        if ($type->getDuration() === null) {
            throw new ActivityDurationNotSetException($type);
        }

        $activityEntity = (new Activity(ActivityEngine::getId($type), $type->getDuration()));
        $this->activityRepository->save($activityEntity);
        $type->setEntityId($activityEntity->getId());
        $type->start();

        $this->eventDispatcher->dispatch(new ActivityStartEvent($type, $subject));

        $activityEntity->setStartedAt(microtime(true));
        $this->activityRepository->save($activityEntity);

        $this->messageBus->dispatch(new ActivityTimeout($type, $subject),[new DelayStamp($this->getMillisecondsDuration($type))]);
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

    #[AsMessageHandler]
    public function activityTimeout(ActivityTimeout $message): void
    {
        $activity = $message->getActivity();
        $activityEntity = $this->activityRepository->find($activity->getEntityId());
        if (!$activityEntity instanceof Activity) {
            return;
        }

        $activityEntity->setCompletedAt(microtime(true));
        $this->activityRepository->save($activityEntity);

        $this->eventDispatcher->dispatch(new ActivityEndEvent($activity, $message->getSubjectId()));
    }
}