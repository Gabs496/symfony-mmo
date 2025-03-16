<?php

namespace App\Engine\MapResource;

use App\Entity\Game\MapSpawnedResource;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\MapResource\Engine\Fullfill\Event\MapResourceFullfill;
use App\GameElement\MapResource\MapResourceSpawn;
use App\GameObject\Map\AbstractBaseMap;
use App\Repository\Game\MapSpawnedResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Random\RandomException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class MapResourceEngine
{
    public function __construct(
        private MapSpawnedResourceRepository $mapSpawnedResourceRepository,
        private GameObjectEngine $gameObjectEngine,
    )
    {

    }
    #[AsEventListener(MapResourceFullfill::class)]
    public function mapFullfill(MapResourceFullfill $event): void
    {
        $this->resourceFullfill($event->getMap(), $event->getMapResourceSpawn());
    }

    public function resourceFullfill(AbstractBaseMap $map, MapResourceSpawn $mapResourceSpawn, bool $full = false): void
    {

        if (!$this->hasFreeSpace($map, $mapResourceSpawn)) {
            return;
        }

        $this->spawnNewResource($map, $mapResourceSpawn);
        while ($full && $this->hasFreeSpace($map, $mapResourceSpawn)) {
            $this->spawnNewResource($map, $mapResourceSpawn);
        }
    }

    private function spawnNewResource(AbstractBaseMap $map, MapResourceSpawn $mapResourceSpawn, int $resourceQuantity = 0): void
    {
        if (!$resourceQuantity) {
            $freeSpace = $this->getFreeSpace($map, $mapResourceSpawn);
            if (!$freeSpace) {
                return;
            }

            try {
                $maxResourceQuantity = min(
                    $this->getFreeSpace($map, $mapResourceSpawn),
                    $mapResourceSpawn->getMaxSpotAvailability()
                );
                $resourceQuantity = random_int(1, $maxResourceQuantity);
            } catch (RandomException $e) {
                $resourceQuantity = 1;
            }
        }

        /** @var AbstractResource $resource */
        $resource = $this->gameObjectEngine->get($mapResourceSpawn->getResourceId());
        $instance = (new MapSpawnedResource(
            $map->getId(),
            $resource,
            $resourceQuantity
        ));
        $this->mapSpawnedResourceRepository->save($instance);
    }

    public function hasFreeSpace(AbstractBaseMap $map, MapResourceSpawn $mapResourceSpawn): bool
    {

        return $this->getFreeSpace($map, $mapResourceSpawn) > 0;
    }

    public function getFreeSpace(AbstractBaseMap $map, MapResourceSpawn $mapResourceSpawn): int
    {
        return $mapResourceSpawn->getMaxGlobalAvailability() - $this->getSpaceTaken($map, $mapResourceSpawn);
    }

    public function getSpaceTaken(AbstractBaseMap $map, MapResourceSpawn $mapResourceSpawn): int
    {
        $spots = $this->mapSpawnedResourceRepository->findBy(['mapId' => $map->getId(), 'resourceId' => $mapResourceSpawn->getResourceId()]);
        return (new ArrayCollection($spots))->reduce(function (int $carry, MapSpawnedResource $spot) {
            return $carry + $spot->getQuantity();
        }, 0);
    }
}