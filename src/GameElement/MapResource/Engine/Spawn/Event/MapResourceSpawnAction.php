<?php

namespace App\GameElement\MapResource\Engine\Spawn\Event;

use App\GameElement\Map\AbstractMap;
use App\GameElement\MapResource\MapResourceSpawn;

readonly class MapResourceSpawnAction
{
    public function __construct(
        private MapResourceSpawn $mapResourceSpawn,
        private AbstractMap      $map,
    ){

    }

    public function getMapResourceSpawn(): MapResourceSpawn
    {
        return $this->mapResourceSpawn;
    }

    public function getMap(): AbstractMap
    {
        return $this->map;
    }
}