<?php

namespace App\GameElement\Combat;

use App\GameElement\Combat\Component\Combat;

interface HasCombatComponentInterface
{
    public static function getCombatManagerClass(): string;
    public function getCombatComponent(): Combat;
}