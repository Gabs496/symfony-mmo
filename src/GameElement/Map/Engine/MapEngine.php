<?php

namespace App\GameElement\Map\Engine;

use App\Entity\Data\MapAvailableActivity;
use App\Entity\Game\MapResource;
use App\GameElement\Activity\Activity;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\GameElement\Map\AbstractMap;
use App\Repository\Data\MapAvailableActivityRepository;
use App\Repository\Game\MapSpawnedMobRepository;
use Random\RandomException;

readonly class MapEngine
{
    public function __construct(
        private MapAvailableActivityRepository $mapAvailableActivityRepository,
        private GameObjectEngine   $gameObjectEngine,
        private MapSpawnedMobRepository $mapSpawnedMobRepository,
    )
    {
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
        $resource = $this->gameObjectEngine->get($mapResource->getResourceId());
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

        $type = (new \ReflectionClass(ResourceGatheringActivity::class))->getAttributes(Activity::class)[0]->getArguments()['id'];
        $instance = (new MapAvailableActivity(
            $mapResource->getMapId(),
            $type,
            $resourceQuantity
        ))
            ->setIcon(sprintf('/map_activity/%s/%s.png', strtolower($type), strtolower($mapResource->getResourceId())))
            ->setName($resource->getName())
        ;
        $mapResource->addSpot($instance);
    }

    public function getAvailableActivities(AbstractMap $map)
    {
        return $this->mapAvailableActivityRepository->findBy(['mapId' => $map->getId()]);
    }

    public function getSpawnedMobs(AbstractMap $map)
    {
        return $this->mapSpawnedMobRepository->findBy(['mapId' => $map->getId()]);
    }
}