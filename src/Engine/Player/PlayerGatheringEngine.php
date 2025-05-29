<?php

namespace App\Engine\Player;

use App\GameElement\Gathering\Event\ResourceGatheringEndedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerGatheringEngine implements EventSubscriberInterface
{
    public function __construct(
//        private MapObjectRepository $mapObjectRepository,
//        private ActivityEngine      $activityEngine,
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
//        $activity = $event->getActivity();
//
//        /** @var MapObjectRepository $mapSpawnedResource */
//        $mapSpawnedResource = $this->mapObjectRepository->find($activity->getResource()->getId());
//        if (!$mapSpawnedResource) {
//            return;
//        }

//        if ($mapSpawnedResource->getQuantity() > 0) {
//            $this->activityEngine->run(new ResourceGatheringActivity($activity->getSubject(), $activity->getResource(), $mapSpawnedResource->getId()));
//        }
    }
}