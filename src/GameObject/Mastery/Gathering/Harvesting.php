<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

readonly class Harvesting extends MasteryType
{
    public static function getId(): string
    {
        return 'HARVESTING';
    }

    public static function getName(): string
    {
        return 'Harvesting';
    }
}