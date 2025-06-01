<?php

namespace App\GameElement\Health\Event;

use App\GameElement\Core\GameObject\GameObjectInterface;

class HealthReachedZeroEvent
{
    public function __construct(
        protected GameObjectInterface $object,
    ) {
    }

    public function getObject(): GameObjectInterface
    {
        return $this->object;
    }
}