<?php

namespace App\GameElement\Mastery;

abstract class MasteryType
{
    public function getMinimumExperience(): float
    {
        return 0.1;
    }
}
