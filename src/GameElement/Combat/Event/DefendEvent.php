<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\Component\Attack;

class DefendEvent
{
    public function __construct(
        protected Attack                  $attack,
        protected CombatOpponentTokenInterface $defender,
    ){}

    public function getAttack(): Attack
    {
        return $this->attack;
    }

    public function getDefender(): CombatOpponentTokenInterface
    {
        return $this->defender;
    }
}