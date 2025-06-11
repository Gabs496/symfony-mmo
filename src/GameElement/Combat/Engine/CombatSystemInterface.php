<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\Damage;
use App\GameElement\Combat\Phase\Defense;

interface CombatSystemInterface
{
    public function calculateDamage(Attack $attack, Defense $defense): Damage;

    //TODO: try another logic
    public static function getBonusAttack(float $damage): float;
}