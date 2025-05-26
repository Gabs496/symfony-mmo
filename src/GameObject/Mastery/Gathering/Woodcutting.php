<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

readonly class Woodcutting extends MasteryType
{
    public static function getId(): string
    {
        return 'WOODCUTTING';
    }

    public static function getName(): string
    {
        return 'Woodcutting';
    }
}