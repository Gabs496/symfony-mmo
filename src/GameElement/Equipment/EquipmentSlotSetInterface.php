<?php

namespace App\GameElement\Equipment;

interface EquipmentSlotSetInterface
{
    /** @return array<string> */
    public function getSlots(): array;
}