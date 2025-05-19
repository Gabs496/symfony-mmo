<?php

namespace App\Engine\Gathering;

use App\Engine\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Gathering\Event\ResourceGatheringEvent;
use App\Repository\Data\ActivityRepository;
use App\Repository\Game\MapSpawnedResourceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class ResourceGatheringEngine implements EventSubscriberInterface
{
    public function __construct(
        private MapSpawnedResourceRepository $mapSpawnedResourceRepository,
        private ActivityRepository  $activityRepository,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ActivityStartEvent::class => [
                ['lockMapSpawnedResourceActivity'],
            ],
            ResourceGatheringEvent::class => [
                ['consumeMapSpawnedResourceActivity'],
            ],
        ];
    }

    public function lockMapSpawnedResourceActivity(ActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $mapSpawnedResource = $this->mapSpawnedResourceRepository->find($activity->getResourceInstanceId());
        $activityEntity = $this->activityRepository->find($event->getActivity()->getEntityId());
        $mapSpawnedResource->startActivity($activityEntity);
        $this->mapSpawnedResourceRepository->save($mapSpawnedResource);
    }

    public function consumeMapSpawnedResourceActivity(ResourceGatheringEvent $event): void
    {
        $activity = $event->getActivity();

        $mapSpawnedResource = $this->mapSpawnedResourceRepository->find($activity->getResourceInstanceId());
        $mapSpawnedResource
            ->consume(1)
            ->endActivity()
        ;
        if ($mapSpawnedResource->isEmpty()) {
            $this->mapSpawnedResourceRepository->remove($mapSpawnedResource);
            return;
        }
        $this->mapSpawnedResourceRepository->save($mapSpawnedResource);
    }
}