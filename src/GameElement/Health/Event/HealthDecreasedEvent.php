<?php

namespace App\GameElement\Health\Event;

use App\GameElement\Health\Component\Health;

class HealthDecreasedEvent
{
    public function __construct(
        protected Health $healthComponent,
    ) {
    }

    public function getHealthComponent(): Health
    {
        return $this->healthComponent;
    }
}