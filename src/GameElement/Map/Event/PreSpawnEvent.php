<?php

namespace App\GameElement\Map\Event;

use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use PennyPHP\Core\GameObjectInterface;

readonly class PreSpawnEvent
{
    public function __construct(
        private MapComponent        $mapComponent,
        private ObjectSpawn         $objectSpawn,
        private GameObjectInterface $object,
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

    public function getObject(): GameObjectInterface
    {
        return $this->object;
    }
}