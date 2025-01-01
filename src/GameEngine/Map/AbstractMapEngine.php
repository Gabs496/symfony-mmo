<?php

namespace App\GameEngine\Map;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Game\MapResource;
use App\GameElement\Map;
use App\GameEngine\Activity\ActivityType;
use App\GameEngine\Resource\ResourceCollection;
use App\Repository\Data\MapAvailableActivityRepository;
use Random\RandomException;
use ReflectionClass;

abstract readonly class AbstractMapEngine
{
    protected Map $map;
    public function __construct(
        private MapAvailableActivityRepository $mapAvailableActivityRepository,
        private ResourceCollection   $resourceCollection,
    )
    {
        $reflection = new ReflectionClass($this);
        foreach ($reflection->getAttributes(Map::class) as $mapAttribute) {
            $this->map = $mapAttribute->newInstance();
            break;
        }
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
        return $this->mapAvailableActivityRepository->findBy(['mapId' => $this->getMap()->getId()]);
    }

    public function getMap(): Map
    {
        return $this->map;
    }
}