<?php

namespace App\GameObject\Mastery\Crafting;

use App\GameElement\Mastery\MasteryType;

class SwordCrafting extends MasteryType
{
    public function getId(): string
    {
        return 'SWORD_CRAFTING';
    }

    public static function getName(): string
    {
        return 'Sword Crafting';
    }
}