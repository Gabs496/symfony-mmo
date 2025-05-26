<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Combat\HasCombatComponentInterface;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\AttackResult;
use App\GameElement\Combat\Phase\Defense;

interface CombatManagerInterface
{
    public function generateAttack(HasCombatComponentInterface $attacker, HasCombatComponentInterface $defender): Attack;
    public function generateDefense(Attack $attack, HasCombatComponentInterface $defender): Defense;
    public function defend(Attack $attack, Defense $defense): AttackResult;
}