<?php

namespace App\GameRule;

use App\Entity\ActivityType;
use App\Entity\Data\MapAvailableActivity;
use App\Entity\Game\MapResource;
use App\Repository\Data\MapAvailableActivityRepository;
use App\Repository\Game\MapResourceRepository;
use Random\RandomException;
use ReflectionException;

readonly class Map
{
    public function __construct(
        private MapResourceRepository $mapResourceRepository,
        private ResourceCollection $resourceCollection,
        private MapAvailableActivityRepository $mapAvailableActivityRepository,
    )
    {
    }

    public function mapResourceFullfill(MapResource $mapResource, bool $full = false): void
    {
        if (!$mapResource->hasFreeSpace()) {
            return;
        }

        $this->spawnNewSpot($mapResource);
        while ($full && $mapResource->hasFreeSpace()) {
            $this->spawnNewSpot($mapResource);
        }

        $this->mapResourceRepository->save($mapResource);
    }

    /**
     * @throws ReflectionException
     */
    private function spawnNewSpot(MapResource $mapResource, int $resourceQuantity = 0): void
    {
        $resource = $this->resourceCollection->getResource($mapResource->getResourceId());
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
            ->setName($resource->getName())
        ;
        $mapResource->addSpot($instance);
    }

    public function getAvailableActivities(string $mapId): array
    {
        return $this->mapAvailableActivityRepository->findBy(['mapId' => $mapId]);
    }
}