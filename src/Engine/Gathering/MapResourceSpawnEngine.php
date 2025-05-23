<?php

namespace App\Engine\Gathering;

use App\Entity\Game\MapSpawnedResource;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\MapResource\Engine\Spawn\Event\MapResourceSpawnAction;
use App\GameElement\MapResource\MapResourceSpawn;
use App\GameObject\Map\AbstractBaseMap;
use App\Repository\Game\MapSpawnedResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Random\RandomException;
use RuntimeException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class MapResourceSpawnEngine
{
    public function __construct(
        private MapSpawnedResourceRepository $mapSpawnedResourceRepository,
        private GameObjectEngine $gameObjectEngine,
    )
    {

    }

    /** TODO: try to create a common engine to manage map fullfill */
    #[AsEventListener(MapResourceSpawnAction::class)]
    public function spawn(MapResourceSpawnAction $event): void
    {
        $map = $event->getMap();
        $mapResourceSpawn = $event->getMapResourceSpawn();

        if (!$this->hasFreeSpace($map, $mapResourceSpawn)) {
            return;
        }

        $randomNumber = bcdiv(random_int(0, 1000000000), 1000000000, 9);
        if (bccomp($randomNumber, $mapResourceSpawn->getSpawnRate(), 9) !== 1) {
            try {
                $this->spawnNewResource($map, $mapResourceSpawn);
            } catch (RuntimeException $e) {}
        }
    }

    private function spawnNewResource(AbstractBaseMap $map, MapResourceSpawn $mapResourceSpawn, int $resourceQuantity = 0): void
    {
        if (!$resourceQuantity) {
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