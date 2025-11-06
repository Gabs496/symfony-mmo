<?php

namespace App\GameElement\Core\GameObject\Event;

use App\GameElement\Core\GameObject\GameObjectInterface;

readonly class GameObjectRemoveEvent
{
    public function __construct(
        private GameObjectInterface $gameObject,
    ){

    }

    public function getGameObject(): GameObjectInterface
    {
        return $this->gameObject;
    }
}