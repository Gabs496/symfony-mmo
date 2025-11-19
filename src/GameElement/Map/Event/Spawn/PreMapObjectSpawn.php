<?php

namespace App\GameElement\Map\Event\Spawn;

use App\Entity\Map\MapObject;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;

class PreMapObjectSpawn
{
    public function __construct(
        protected MapObject   $mapObject,
        protected ObjectSpawn $objectSpawn,
    )
    {
    }

    public function getMapObject(): MapObject
    {
        return $this->mapObject;
    }

    public function getObjectSpawn(): ObjectSpawn
    {
        return $this->objectSpawn;
    }
}