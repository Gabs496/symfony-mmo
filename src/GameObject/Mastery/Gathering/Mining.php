<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

class Mining extends MasteryType
{
    public function __toString(): string
    {
        return 'MINING';
    }
}