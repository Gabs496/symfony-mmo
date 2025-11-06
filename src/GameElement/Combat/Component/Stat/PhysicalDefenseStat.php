<?php

namespace App\GameElement\Combat\Component\Stat;

readonly class PhysicalDefenseStat extends DefensiveStat
{

    public static function getLabel(): string
    {
        return 'Physical defense';
    }
}