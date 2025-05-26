<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\HasCombatComponentInterface;
use App\GameElement\Combat\Phase\Attack;

readonly class AttackEvent
{
    public function __construct(
        protected Attack                      $attack,
        protected HasCombatComponentInterface $defender,
    ){
    }

    public function getAttack(): Attack
    {
        return $this->attack;
    }

    public function getDefender(): HasCombatComponentInterface
    {
        return $this->defender;
    }
}