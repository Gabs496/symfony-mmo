<?php

namespace App\GameElement\Health\Engine;

use App\Engine\Math;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\Health;
use App\GameElement\Health\Event\HealthDecreasedEvent;
use App\GameElement\Health\Event\HealthReachedZeroEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class HealthEngine
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }
    public function decreaseCurrentHealth(GameObjectInterface $object, float $value): void
    {
        $health = $object->getComponent(Health::class);
        $currentHealth = $health->getCurrentHealth();
        $newHealth = max(Math::sub($currentHealth, $value), 0.0);
        $health->setCurrentHealth($newHealth);

        $this->eventDispatcher->dispatch(new HealthDecreasedEvent($object, $health));

        if (round($newHealth, 5) === 0.0) {
            $this->eventDispatcher->dispatch(new HealthReachedZeroEvent($object));
        }
    }
}