<?php

namespace App\GameObject\Mastery\Gathering;

use App\GameElement\Mastery\MasteryType;

class Woodcutting extends MasteryType
{
    public function __toString(): string
    {
        return 'WOODCUTTING';
    }
}