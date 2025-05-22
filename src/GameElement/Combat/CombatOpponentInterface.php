<?php

namespace App\GameElement\Combat;

interface CombatOpponentInterface
{
    public static function getCombatManagerClass(): string;
}