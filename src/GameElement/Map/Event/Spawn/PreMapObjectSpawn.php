<?php

namespace App\GameElement\Map\Event\Spawn;

use PennyPHP\Core\GameObject\Entity\GameObject;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;

class PreMapObjectSpawn
{
    public function __construct(
        private readonly MapComponent $mapComponent,
        private readonly ObjectSpawn  $objectSpawn,
        private readonly GameObject   $object,
    )
    {
    }

    public function getMapComponent(): MapComponent
    {
        return $this->mapComponent;
    }

    public function getObjectSpawn(): ObjectSpawn
    {
        return $this->objectSpawn;
    }

    public function getObject(): GameObject
    {
        return $this->object;
    }
}