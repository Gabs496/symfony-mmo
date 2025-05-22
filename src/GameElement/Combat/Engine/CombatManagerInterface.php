<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\Defense;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface CombatManagerInterface
{
    public function exchangeToken(CombatOpponentTokenInterface $token): CombatOpponentInterface;
    public function generateAttack(CombatOpponentInterface $attacker, CombatOpponentInterface $defender): Attack;
    public function generateDefense(Attack $attack, CombatOpponentInterface $defender): Defense;
    public function defend(Attack $attack, Defense $defense, EventDispatcherInterface $callbackDispatcher): void;
}