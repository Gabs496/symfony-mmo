<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\AttackResult;
use App\GameElement\Combat\Phase\Defense;

interface CombatManagerInterface
{
    public function generateAttack(CombatOpponentInterface $attacker, CombatOpponentInterface $defender): Attack;
    public function generateDefense(Attack $attack, CombatOpponentInterface $defender): Defense;
    public function defend(Attack $attack, Defense $defense): AttackResult;
}