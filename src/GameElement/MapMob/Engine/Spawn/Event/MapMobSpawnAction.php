<?php

namespace App\GameElement\MapMob\Engine\Spawn\Event;

use App\GameElement\Map\AbstractMap;
use App\GameElement\MapMob\MapMobSpawn;

readonly class MapMobSpawnAction
{
    public function __construct(
        private MapMobSpawn $mapMobSpawn,
        private AbstractMap $map,
    ){

    }

    public function getMapMobSpawn(): MapMobSpawn
    {
        return $this->mapMobSpawn;
    }

    public function getMap(): AbstractMap
    {
        return $this->map;
    }
}