<?php

namespace App\Engine\Mob;

use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\MapMob\Engine\Fullfill\Event\MapMobFullfill;
use App\GameElement\MapMob\MapMobSpawn;
use App\GameElement\Mob\AbstractMob;
use App\GameObject\Map\AbstractBaseMap;
use App\Repository\Game\MapSpawnedMobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class MobSpawnEngine
{
    public function __construct(
        private MapSpawnedMobRepository $mapSpawnedMobRepository,
        private GameObjectEngine        $gameObjectEngine,
    )
    {

    }
    #[AsEventListener(MapMobFullfill::class)]
    public function mapFullfill(MapMobFullfill $event): void
    {
        $this->mobFullfill($event->getMap(), $event->getMapMobSpawn());
    }

    private function mobFullfill(AbstractBaseMap $map, MapMobSpawn $mapMobSpawn): void
    {

        if (!$this->hasFreeSpace($map, $mapMobSpawn)) {
            return;
        }

        $randomNumber = bcdiv(random_int(0, 1000000000), 1000000000, 9);
        if (bccomp($randomNumber, $mapMobSpawn->getSpawnRate(), 9) !== 1) {
            $this->spawnNewMob($map, $mapMobSpawn);
        }
    }

    private function spawnNewMob(AbstractBaseMap $map, MapMobSpawn $mapMobSpawn): void
    {
        $freeSpace = $this->getFreeSpace($map, $mapMobSpawn);
        if (!$freeSpace) {
            return;
        }

        /** @var AbstractMob $mob */
        $mob = $this->gameObjectEngine->get($mapMobSpawn->getMobId());
        $instance = (new MapSpawnedMob($map, $mob, $mob->getComponents()));
        $this->mapSpawnedMobRepository->save($instance);
    }

    private function hasFreeSpace(AbstractBaseMap $map, MapMobSpawn $mapMobSpawn): bool
    {

        return $this->getFreeSpace($map, $mapMobSpawn) > 0;
    }

    private function getFreeSpace(AbstractBaseMap $map, MapMobSpawn $mapMobSpawn): int
    {
        return $mapMobSpawn->getMaxAvailability() - $this->getSpaceTaken($map, $mapMobSpawn);
    }

    private function getSpaceTaken(AbstractBaseMap $map, MapMobSpawn $mapMobSpawn): int
    {
        $spots = $this->mapSpawnedMobRepository->findBy(['mapId' => $map->getId(), 'mobId' => $mapMobSpawn->getMobId()]);
        return (new ArrayCollection($spots))->reduce(function (int $carry) {
            return $carry + 1;
        }, 0);
    }
}