<?php

namespace App\GameElement\Combat\Component\Stat;

readonly class PhysicalAttackStat extends OffensiveStat
{

    public static function getLabel(): string
    {
        return 'Phisical attack';
    }
}