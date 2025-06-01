<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\AttackResult;
use App\GameElement\Combat\Phase\Defense;
use App\GameElement\Core\GameObject\GameObjectInterface;

interface CombatManagerInterface
{
    public function generateAttack(GameObjectInterface $attacker, GameObjectInterface $defender): Attack;
    public function generateDefense(Attack $attack, GameObjectInterface $defender): Defense;
    public function defend(Attack $attack, Defense $defense): AttackResult;
}