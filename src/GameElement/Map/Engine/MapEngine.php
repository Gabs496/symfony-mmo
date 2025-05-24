<?php

namespace App\GameElement\Map\Engine;

use App\GameElement\Map\AbstractMap;
use App\Repository\Game\MapSpawnedMobRepository;
use App\Repository\Game\MapSpawnedResourceRepository;

readonly class MapEngine
{
    public function __construct(
        private MapSpawnedResourceRepository $mapSpawnedResourceRepository,
        private MapSpawnedMobRepository $mapSpawnedMobRepository,
    )
    {
    }

    //TODO: move to implementation
    public function getSpawnedResources(AbstractMap $map)
    {
        return $this->mapSpawnedResourceRepository->findBy(['mapId' => $map->getId()]);
    }

    public function getSpawnedMobs(AbstractMap $map)
    {
        return $this->mapSpawnedMobRepository->findBy(['mapId' => $map->getId()]);
    }
}