<?php

namespace App\GameObject\Map;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Game\MapResource;
use App\GameObject\AbstractGameObject;
use App\GameObject\ResourceCollection;
use App\GameRule\Activity\ActivityType;
use App\Repository\Data\MapAvailableActivityRepository;
use Random\RandomException;

abstract readonly class AbstractMapObject extends AbstractGameObject
{
    public function __construct(
        private MapAvailableActivityRepository $mapAvailableActivityRepository,
        private ResourceCollection   $resourceCollection,
    )
    {
        parent::__construct();
    }
    public function resourceFullfill(MapResource $mapResource, bool $full = false): void
    {
        if (!$mapResource->hasFreeSpace()) {
            return;
        }

        $this->spawnNewResource($mapResource);
        while ($full && $mapResource->hasFreeSpace()) {
            $this->spawnNewResource($mapResource);
        }

        $this->mapAvailableActivityRepository->save($mapResource);
    }


    private function spawnNewResource(MapResource $mapResource, int $resourceQuantity = 0): void
    {
        $resource = $this->resourceCollection->get($mapResource->getResourceId());
        if (!$resourceQuantity) {
            $freeSpace = $mapResource->getFreeSpace();
            if (!$freeSpace) {
                return;
            }

            try {
                $maxResourceQuantity = min(
                    $mapResource->getFreeSpace(),
                    $mapResource->getMaxSpotAvailability()
                );
                $resourceQuantity = random_int(1, $maxResourceQuantity);
            } catch (RandomException $e) {
                $resourceQuantity = 1;
            }
        }

        $instance = (new MapAvailableActivity(
            $mapResource->getMapId(),
            ActivityType::RESOURCE_GATHERING,
            $resourceQuantity
        ))
            ->setIcon(sprintf('/map_activity/%s/%s.png', strtolower(ActivityType::RESOURCE_GATHERING->value), strtolower($mapResource->getResourceId())))
            ->setName($resource->getElement()->getName())
        ;
        $mapResource->addSpot($instance);
    }

    public function getAvailableActivities()
    {
        return $this->mapAvailableActivityRepository->findBy(['mapId' => $this->element->getId()]);
    }
}