<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityTimeoutEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEvent;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class ResourceGatheringEngineExtension implements ActivityEngineExtensionInterface, EventSubscriberInterface
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected RewardEngine $rewardEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeActivityStartEvent::class => [
                ['setDuration', 0]
            ],
            ActivityTimeoutEvent::class => [
                ['dispatchGathering', 0]
            ],
            ActivityEndEvent::class => [
                ['reward', 0],
                ['dispatchEnd', 0]
            ],
        ];
    }

    public  function setDuration(BeforeActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $resource = $activity->getResource();
        $activity->setDuration($resource->getGatheringTime());
    }

    public function dispatchGathering(ActivityTimeoutEvent $event): void
    {
        $timeout = $event->getTimeout();
        $activity = $timeout->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $this->eventDispatcher->dispatch(new ResourceGatheringEvent($activity, $timeout->getSubject()));
    }

    public function reward(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        foreach ($activity->getRewards() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $event->getSubject()));
        }
    }

    public function dispatchEnd(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $this->eventDispatcher->dispatch(new ResourceGatheringEndedEvent($activity, $event->getSubject()));
    }
}