<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

readonly class Harvesting extends MasteryType
{
    public const string ID = 'HARVESTING';

    public static function getId(): string
    {
        return self::ID;
    }

    public static function getName(): string
    {
        return 'Harvesting';
    }
}