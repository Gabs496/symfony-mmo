<?php

namespace App\GameObject\Mastery\Combat;

use App\GameElement\Mastery\MasteryType;

class Health extends MasteryType
{
    public function __toString(): string
    {
        return 'HEALTH';
    }
}