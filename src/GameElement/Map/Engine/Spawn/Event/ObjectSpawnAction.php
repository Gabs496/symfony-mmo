<?php

namespace App\GameElement\Map\Engine\Spawn\Event;

use App\GameElement\Map\AbstractMap;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;

readonly class ObjectSpawnAction
{
    public function __construct(
        private ObjectSpawn $objectSpawn,
        private AbstractMap $map,
    ){

    }

    public function getObjectSpawn(): ObjectSpawn
    {
        return $this->objectSpawn;
    }

    public function getMap(): AbstractMap
    {
        return $this->map;
    }
}