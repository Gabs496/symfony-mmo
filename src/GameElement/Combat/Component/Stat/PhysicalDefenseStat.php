<?php

namespace App\GameElement\Combat\Component\Stat;

class PhysicalDefenseStat extends DefensiveStat
{

    public static function getLabel(): string
    {
        return 'Physical defense';
    }
}