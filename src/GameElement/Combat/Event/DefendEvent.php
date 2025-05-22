<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\Defense;

class DefendEvent
{
    public function __construct(
        protected Attack  $attack,
        protected Defense $defense,
    ){}

    public function getAttack(): Attack
    {
        return $this->attack;
    }

    public function getDefender(): Defense
    {
        return $this->defense;
    }
}