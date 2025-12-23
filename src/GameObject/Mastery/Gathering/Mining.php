<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

readonly class Mining extends MasteryType
{
    public const string ID = 'MINING';

    public static function getId(): string
    {
        return self::ID;
    }

    public static function getName(): string
    {
        return 'Mining';
    }
}