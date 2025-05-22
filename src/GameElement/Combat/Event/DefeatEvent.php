<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\Defense;

readonly class DefeatEvent
{
    public function __construct(
        private Attack  $attack,
        private Defense $defense,
    )
    {
    }

    public function getAttack(): Attack
    {
        return $this->attack;
    }

    public function getDefense(): Defense
    {
        return $this->defense;
    }
}