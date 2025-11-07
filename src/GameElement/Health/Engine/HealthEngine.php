<?php

namespace App\GameElement\Health\Engine;

use App\Engine\Math;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\HealthComponent;
use App\GameElement\Health\Event\HealthModifiedEvent;
use App\GameElement\Health\Event\HealthReachedZeroEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class HealthEngine
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function modifyCurrentHealth(GameObjectInterface $object, float $value): void
    {
        if ($value === 0.0) {
            return;
        }

        $health = $object->getComponent(HealthComponent::getId());
        $currentHealth = $health->getCurrentHealth();
        $newHealth = min(max(0.0, Math::add($currentHealth, $value)), $health->getMaxHealth());
        $health->setCurrentHealth($newHealth);

        $this->eventDispatcher->dispatch(new HealthModifiedEvent($object, $health));

        if (round($newHealth, 5) === 0.0) {
            $this->eventDispatcher->dispatch(new HealthReachedZeroEvent($object));
        }
    }

    public function decreaseCurrentHealth(GameObjectInterface $object, float $value): void
    {
        $this->modifyCurrentHealth($object, -$value);
    }
}