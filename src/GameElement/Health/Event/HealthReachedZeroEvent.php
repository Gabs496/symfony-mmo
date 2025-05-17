<?php

namespace App\GameElement\Health\Event;

use App\GameElement\Health\HasHealthComponentInterface;

class HealthReachedZeroEvent
{
    public function __construct(
        protected HasHealthComponentInterface $object,
    ) {
    }

    public function getObject(): HasHealthComponentInterface
    {
        return $this->object;
    }
}