<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

class Mining extends MasteryType
{
    public function getId(): string
    {
        return 'MINING';
    }

    public static function getName(): string
    {
        return 'Mining';
    }
}