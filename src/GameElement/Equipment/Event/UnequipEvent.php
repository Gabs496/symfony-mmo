<?php

namespace App\GameElement\Equipment\Event;

use PennyPHP\Core\GameObject\Entity\GameObject;

readonly class UnequipEvent
{
    public function __construct(
        private GameObject $equipment,
        private GameObject $from,
        private string     $slot
    )
    {

    }

    public function getEquipment(): GameObject
    {
        return $this->equipment;
    }

    public function getFrom(): GameObject
    {
        return $this->from;
    }

    public function getSlot(): string
    {
        return $this->slot;
    }
}