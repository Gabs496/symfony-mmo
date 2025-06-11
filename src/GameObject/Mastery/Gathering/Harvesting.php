<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

readonly class FoodGathering extends MasteryType
{
    public static function getId(): string
    {
        return 'FOOD_GATHERING';
    }

    public static function getName(): string
    {
        return 'Gathering';
    }
}