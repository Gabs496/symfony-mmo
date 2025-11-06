<?php

namespace App\GameElement\Health\Event;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\HealthComponent;

class HealthModifiedEvent
{
    public function __construct(
        protected GameObjectInterface $object,
        protected HealthComponent     $health,
    ) {
    }

    public function getObject(): GameObjectInterface
    {
        return $this->object;
    }

    public function getHealth(): HealthComponent
    {
        return $this->health;
    }
}