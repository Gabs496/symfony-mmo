<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

readonly class Woodcutting extends MasteryType
{
    public const string ID = 'WOODCUTTING';

    public static function getId(): string
    {
        return self::ID;
    }

    public static function getName(): string
    {
        return 'Woodcutting';
    }
}