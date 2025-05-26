<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

readonly class Mining extends MasteryType
{
    public static function getId(): string
    {
        return 'MINING';
    }

    public static function getName(): string
    {
        return 'Mining';
    }
}