<?php

namespace App\Engine\MapResource;

use App\Entity\Data\PlayerCharacter;
use App\Entity\Game\MapSpawnedResource;
use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\ActivityStartEvent;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\Repository\Data\ActivityRepository;
use App\Repository\Game\MapSpawnedResourceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class ActivityHandler implements EventSubscriberInterface
{
    public function __construct(
        private ActivityEngine     $activityEngine,
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
//                ['streamActvityStart'],
            ],
            ActivityEndEvent::class => [
                ['consumeMapSpawnedResourceActivity'],
                ['continueUntillEmpty', -1],
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

//    public function streamActvityStart(ActivityStartEvent $event): void
//    {
//        $activity = $event->getActivity();
//        if (!$activity instanceof ResourceGatheringActivity) {
//            return;
//        }
//
//        $subject = $event->getSubject();
//        if (!$subject instanceof PlayerCharacter) {
//            return;
//        }
//
//        $mapSpawnedResource = $this->mapSpawnedResourceRepository->find($activity->getResourceInstanceId());
//        $this->hub->publish(new Update(['mapAvailableActivities_' . $mapSpawnedResource->getMapId()],
//            $this->twig->load('map/MapAvailableActivity.stream.html.twig')->renderBlock('update', ['entity' => $mapSpawnedResource, 'id' => $mapSpawnedResource->getId()]),
//        true
//        ));
//    }

    public function consumeMapSpawnedResourceActivity(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

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

    public function continueUntillEmpty(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof ResourceGatheringActivity) {
            return;
        }

        $mapSpawnedResource = $this->mapSpawnedResourceRepository->find($activity->getResourceInstanceId());
        if (!$mapSpawnedResource instanceof MapSpawnedResource) {
            return;
        }

        // TODO: move to PlayerGatheringEngine
        if ($mapSpawnedResource->getQuantity() > 0) {
            $this->activityEngine->run($event->getSubject(), new ResourceGatheringActivity($activity->getResource(), $mapSpawnedResource->getId()));
        }
    }
}