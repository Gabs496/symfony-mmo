<?php

namespace App\GameElement\Map\Event\Spawn;

use App\Entity\Game\MapObject;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;

class PreMapObjectSpawn
{
    public function __construct(
        protected MapObject $object,
        protected ObjectSpawn $objectSpawn,
    )
    {
    }

    public function getObject(): MapObject
    {
        return $this->object;
    }

    public function getObjectSpawn(): ObjectSpawn
    {
        return $this->objectSpawn;
    }
}