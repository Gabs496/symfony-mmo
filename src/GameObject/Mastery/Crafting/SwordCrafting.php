<?php

namespace App\GameObject\Mastery\Crafting;

use App\GameElement\Mastery\MasteryType;

readonly class SwordCrafting extends MasteryType
{
    public static function getId(): string
    {
        return 'SWORD_CRAFTING';
    }

    public static function getName(): string
    {
        return 'Sword Crafting';
    }
}