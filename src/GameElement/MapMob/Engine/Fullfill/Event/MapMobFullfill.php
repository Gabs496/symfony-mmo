<?php

namespace App\GameElement\MapMob\Engine\Fullfill\Event;

use App\GameElement\Map\AbstractMap;
use App\GameElement\MapMob\MapMobSpawn;

readonly class MapMobFullfill
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