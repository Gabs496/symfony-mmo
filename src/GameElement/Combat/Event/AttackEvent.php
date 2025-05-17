<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\CombatOpponentInterface;

readonly class AttackEvent
{
    public function __construct(
        protected CombatOpponentInterface $attacker,
        protected CombatOpponentInterface $defender,
    ){}

    public function getAttacker(): CombatOpponentInterface
    {
        return $this->attacker;
    }

    public function getDefender(): CombatOpponentInterface
    {
        return $this->defender;
    }
}