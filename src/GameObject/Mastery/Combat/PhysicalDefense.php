<?php

namespace App\GameObject\Mastery\Combat;

use App\GameElement\Mastery\MasteryType;

class PhysicalDefense extends MasteryType
{
    public function __toString(): string
    {
        return 'DEFENSE';
    }
}