<?php

namespace App\GameElement\Equipment;

enum BaseEquipmentSlotSet: string implements EquipmentSlotSetInterface
{

    case HEAD = "head";
    case BODY = "body";
    case LEFT_HAND = "left_hand";
    case RIGHT_HAND = "right_hand";
    case FOOT = "foot";


    public function getSlots(): array
    {
        return self::cases();
    }
}
