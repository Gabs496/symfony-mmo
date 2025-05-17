<?php

namespace App\GameElement\Health\Event;

use App\GameElement\Health\Component\Health;
use App\GameElement\Health\HasHealthComponentInterface;

class HealthDecreasedEvent
{
    public function __construct(
        protected HasHealthComponentInterface $object,
        protected Health $health,
    ) {
    }

    public function getObject(): HasHealthComponentInterface
    {
        return $this->object;
    }

    public function getHealth(): Health
    {
        return $this->health;
    }
}