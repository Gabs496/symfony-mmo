<?php

namespace App\GameElement\Equipment\Event;

use PennyPHP\Core\GameObjectInterface;

readonly class UnequipEvent
{
    public function __construct(
        private GameObjectInterface $equipment,
        private GameObjectInterface $from,
        private string     $slot
    )
    {

    }

    public function getEquipment(): GameObjectInterface
    {
        return $this->equipment;
    }

    public function getFrom(): GameObjectInterface
    {
        return $this->from;
    }

    public function getSlot(): string
    {
        return $this->slot;
    }
}