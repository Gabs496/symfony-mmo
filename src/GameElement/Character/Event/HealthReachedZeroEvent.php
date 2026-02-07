<?php

namespace App\GameElement\Character\Event;

use PennyPHP\Core\GameObject\GameObjectInterface;

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