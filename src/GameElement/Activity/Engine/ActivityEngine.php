<?php

namespace App\GameElement\Activity\Engine;

use App\Entity\Data\Activity;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Activity\Exception\ActivityUnexpectedStopException;
use App\GameElement\Activity\Message\ActivityTimeout;
use App\GameElement\Core\Token\TokenEngine;
use App\Repository\Data\ActivityRepository;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

#[AsMessageHandler(handles: ActivityTimeout::class, method: 'activityTimeout')]
readonly class ActivityEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private ActivityRepository       $activityRepository,
        private MessageBusInterface      $messageBus,
        protected TokenEngine            $tokenEngine,
        /** @var iterable<ActivityEngineExtensionInterface> */
        #[AutowireIterator('activity.engine_extension')]
        protected iterable               $extensions,
        private CacheInterface           $gameObjectCache,
    )
    {
    }

    public function run(AbstractActivity $activity): void
    {
        $extension = $this->getExtension($activity);
        $extension->beforeStart($activity);
        $this->eventDispatcher->dispatch(new BeforeActivityStartEvent($activity));

        $duration = $extension->getDuration($activity);
        $activityEntity = (new Activity($activity->getId(), $duration));
        $this->activityRepository->save($activityEntity);
        $activity->setEntityId($activityEntity->getId());

        $this->eventDispatcher->dispatch(new ActivityStartEvent($activity));

        $activityEntity->setStartedAt(microtime(true));
        $this->activityRepository->save($activityEntity);

        $activity->clear();
        $this->messageBus->dispatch(new ActivityTimeout($activity),[new DelayStamp($this->getMillisecondsDuration($duration))]);
    }

    protected function getMillisecondsDuration(float $seconds): int
    {
        return (int)bcmul($seconds, 1000, 4);
    }

    public function activityTimeout(ActivityTimeout $message): void
    {
        try {
            $activity = $message->getActivity();
            $extension = $this->getExtension($activity);

            $activityEntity = $this->activityRepository->find($activity->getEntityId());
            if (!$activityEntity instanceof Activity) {
                throw new RuntimeException('Activity not found (id ' . $activityEntity->getId() . ')');
            }

            $activity->setSubject($this->tokenEngine->exchange($activity->getSubjectToken()));

            $extension->onComplete($activity);
            $activityEntity->setCompletedAt(microtime(true));
            $this->activityRepository->save($activityEntity);
            $extension->onFinish($activity);
            $this->eventDispatcher->dispatch(new ActivityEndEvent($activity));
        } catch (Throwable $e) {
            if (isset($extension)) {
                $extension->cancel($activity);
            }
            throw new ActivityUnexpectedStopException($activity, $e);
        }
    }

    protected function getExtension(AbstractActivity $activity): ActivityEngineExtensionInterface
    {
        return $this->gameObjectCache->get('activity_extension_' . str_replace('\\', '', $activity->getId()), function (ItemInterface $item) use ($activity) {
            foreach ($this->extensions as $extension) {
                if ($extension->supports($activity)) {
                    return $extension;
                }
            }

            //TODO: create a custom exception for this case
            throw new InvalidArgumentException('Extension not found for activity ' . $activity::class);
        });
    }
}