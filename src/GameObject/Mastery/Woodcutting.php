<?php

namespace App\GameObject\Mastery;

use App\GameElement\Mastery\MasteryType;

class Woodcutting extends MasteryType
{
    public function __toString(): string
    {
        return 'WOODCUTTING';
    }
}