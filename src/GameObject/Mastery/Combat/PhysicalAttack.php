<?php

namespace App\GameObject\Mastery\Combat;

use App\GameElement\Mastery\MasteryType;

class PhysicalAttack extends MasteryType
{
    public function __toString(): string
    {
        return 'ATTACK';
    }
}