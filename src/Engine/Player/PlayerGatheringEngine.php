<?php

namespace App\Engine\Player;

use App\Entity\Game\MapSpawnedResource;
use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use App\Repository\Game\MapSpawnedResourceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerGatheringEngine implements EventSubscriberInterface
{
    public function __construct(
        private MapSpawnedResourceRepository $mapSpawnedResourceRepository,
        private ActivityEngine $activityEngine,
    )
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            ResourceGatheringEndedEvent::class => [
                ['continueUntillEmpty', 0],
            ],
        ];
    }

    public function continueUntillEmpty(ResourceGatheringEndedEvent $event): void
    {
        $activity = $event->getActivity();

        $mapSpawnedResource = $this->mapSpawnedResourceRepository->find($activity->getResourceInstanceId());
        if (!$mapSpawnedResource instanceof MapSpawnedResource) {
            return;
        }

        if ($mapSpawnedResource->getQuantity() > 0) {
            $this->activityEngine->run($event->getSubject(), new ResourceGatheringActivity($activity->getResource(), $mapSpawnedResource->getId()));
        }
    }
}