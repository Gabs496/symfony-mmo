<?php

namespace App\GameElement\Equipment\Event;

use PennyPHP\Core\GameObject\Entity\GameObject;

readonly class EquipEvent
{
    public function __construct(
        private GameObject $equipment,
        private GameObject $to,
        private string $slot
    )
    {

    }

    public function getEquipment(): GameObject
    {
        return $this->equipment;
    }

    public function getTo(): GameObject
    {
        return $this->to;
    }

    public function getSlot(): string
    {
        return $this->slot;
    }
}