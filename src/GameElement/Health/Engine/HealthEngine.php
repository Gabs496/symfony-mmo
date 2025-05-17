<?php

namespace App\GameElement\Health\Engine;

use App\GameElement\Health\Component\Health;
use App\GameElement\Health\Event\HealthDecreasedEvent;
use App\GameElement\Health\Event\HealthReachedZeroEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

readonly class HealthEngine
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }
    public function decreaseCurrentHealth(Health $health, float $value): void
    {
        $currentHealth = $health->getCurrentHealth();
        $newHealth = max($currentHealth - $value, 0.0);
        $health->setCurrentHealth($newHealth);

        $this->eventDispatcher->dispatch(new HealthDecreasedEvent($health));

        if (round($newHealth, 5) === 0.0) {
            $this->eventDispatcher->dispatch(new HealthReachedZeroEvent($health));
        }
    }
}