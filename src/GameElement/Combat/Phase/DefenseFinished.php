<?php

namespace App\GameElement\Combat\Phase;

readonly class DefenseFinished
{
    public function __construct(
        protected Attack $attack,
        protected Defense $defense,
        protected AttackResult $attackResult,
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

    public function getAttackResult(): AttackResult
    {
        return $this->attackResult;
    }
}