<?php

namespace App\GameElement\Map\Engine;

use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use PennyPHP\Core\Engine\GameObjectEngine;
use PennyPHP\Core\GameObjectInterface;

readonly class MapSpawnEngine
{
    public function __construct(
        private GameObjectEngine         $gameObjectEngine,
    )
    {

    }

    public function spawnNewObject(MapComponent $map, ObjectSpawn $objectSpawn): GameObjectInterface
    {
        $instance = $this->gameObjectEngine->make($objectSpawn->getPrototypeId());
        $instance->setComponent(new InMapComponent($map, 'field'));
        return $instance;
    }
}