<?php

namespace App\GameObject\Mastery\Crafting;

use App\GameElement\Mastery\MasteryType;

readonly class SwordCrafting extends MasteryType
{
    public function __construct()
    {
        parent::__construct('SWORD_CRAFTING');
    }

    public static function getName(): string
    {
        return 'Sword Crafting';
    }
}