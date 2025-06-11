<?php

namespace App\GameElement\Health\Event;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\Health;

class HealthModiefiedEvent
{
    public function __construct(
        protected GameObjectInterface $object,
        protected Health $health,
    ) {
    }

    public function getObject(): GameObjectInterface
    {
        return $this->object;
    }

    public function getHealth(): Health
    {
        return $this->health;
    }
}