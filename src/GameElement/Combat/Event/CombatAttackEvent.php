<?php

namespace App\GameElement\Combat\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CombatAttackEvent extends Event
{
    public function __construct(
        private readonly object $attacker,
        private readonly float  $damage,
        private readonly object $defender,
    )
    {
    }

    public function getAttacker(): object
    {
        return $this->attacker;
    }

    public function getDefender(): object
    {
        return $this->defender;
    }

    public function getDamage(): float
    {
        return $this->damage;
    }
}