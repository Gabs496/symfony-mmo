<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Reward\RewardApply;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class ResourceGatheringEngineExtension implements ActivityEngineExtensionInterface
{
    public function __construct(
        private GameObjectEngine     $gameObjectEngine,
        private MessageBusInterface $messageBus,
    )
    {
    }

    #[AsEventListener(BeforeActivityStartEvent::class)]
    public  function beforeActivityStart(BeforeActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $resource = $this->gameObjectEngine->get($activity->getMapAvailableActivity()->getMapResource()->getResourceId());
        $activity->setDuration($resource->getGatheringTime());
    }

    #[AsEventListener(ActivityEndEvent::class)]
    public function onActivityEnd(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        foreach ($activity->getRewards() as $reward) {
            $this->messageBus->dispatch(new RewardApply($reward, $event->getSubject()));
        }
    }
}