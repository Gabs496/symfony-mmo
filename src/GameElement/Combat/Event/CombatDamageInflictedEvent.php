<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\StatCollection;
use Symfony\Contracts\EventDispatcher\Event;

class CombatDamageInflictedEvent extends Event
{
    protected bool $isDefenderAlive = true;

    public function __construct(
        private readonly object $attacker,
        private readonly float  $damage,
        private readonly object $defender,
        private readonly StatCollection $attackerStats,
        private readonly StatCollection $defenderStats,
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

    public function isDefenderAlive(): bool
    {
        return $this->isDefenderAlive;
    }

    public function setIsDefenderAlive(bool $isDefenderAlive): void
    {
        $this->isDefenderAlive = $isDefenderAlive;
    }

    public function getAttackerStats(): StatCollection
    {
        return $this->attackerStats;
    }

    public function getDefenderStats(): StatCollection
    {
        return $this->defenderStats;
    }
}