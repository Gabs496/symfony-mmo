<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\Component\Attack;

class DefendEvent
{
    public function __construct(
        protected Attack                  $attack,
        protected CombatOpponentInterface $defender,
    ){}

    public function getAttack(): Attack
    {
        return $this->attack;
    }

    public function getDefender(): CombatOpponentInterface
    {
        return $this->defender;
    }
}