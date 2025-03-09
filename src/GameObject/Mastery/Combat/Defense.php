<?php

namespace App\GameObject\Mastery\Combat;

use App\GameElement\Mastery\MasteryType;

class Defense extends MasteryType
{
    public function __toString(): string
    {
        return 'DEFENSE';
    }
}