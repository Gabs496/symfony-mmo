<?php

namespace App\GameElement\Character\Engine;

use App\Engine\Math;
use App\GameElement\Character\Component\CharacterComponent;
use App\GameElement\Character\Event\HealthModifiedEvent;
use App\GameElement\Character\Event\HealthReachedZeroEvent;
use PennyPHP\Core\GameObject\GameObjectInterface;
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

        $character = $object->getComponent(CharacterComponent::class);
        $currentHealth = $character->getHealth();
        $newHealth = min(max(0.0, Math::add($currentHealth, $value)), $character->getMaxHealth());
        $character->setHealth($newHealth);

        $this->eventDispatcher->dispatch(new HealthModifiedEvent($object, $character));

        if (round($newHealth, 5) === 0.0) {
            $this->eventDispatcher->dispatch(new HealthReachedZeroEvent($object));
        }
    }

    public function decreaseCurrentHealth(GameObjectInterface $object, float $value): void
    {
        $this->modifyCurrentHealth($object, -$value);
    }
}