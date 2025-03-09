<?php

namespace App\GameObject\Mastery\Crafting;

use App\GameElement\Mastery\MasteryType;

class SwordCrafting extends MasteryType
{
    public function __toString(): string
    {
        return 'SWORD_CRAFTING';
    }
}