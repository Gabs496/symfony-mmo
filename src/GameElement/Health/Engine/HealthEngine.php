<?php

namespace App\GameElement\Health\Engine;

use App\GameElement\Health\Event\HealthDecreasedEvent;
use App\GameElement\Health\Event\HealthReachedZeroEvent;
use App\GameElement\Health\HasHealthComponentInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class HealthEngine
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }
    public function decreaseCurrentHealth(HasHealthComponentInterface $object, float $value): void
    {
        $health = $object->getHealth();
        $currentHealth = $health->getCurrentHealth();
        $newHealth = max($currentHealth - $value, 0.0);
        $health->setCurrentHealth($newHealth);

        $this->eventDispatcher->dispatch(new HealthDecreasedEvent($object, $health));

        if (round($newHealth, 5) === 0.0) {
            $this->eventDispatcher->dispatch(new HealthReachedZeroEvent($object));
        }
    }
}