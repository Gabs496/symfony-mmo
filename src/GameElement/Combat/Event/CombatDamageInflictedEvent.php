<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\StatCollection;
use Symfony\Contracts\EventDispatcher\Event;

class CombatDamageInflictedEvent extends Event
{
    public function __construct(
        private readonly CombatOpponentInterface $attacker,
        private readonly float                   $damage,
        private readonly CombatOpponentInterface $defender,
        private readonly StatCollection          $attackerStats,
        private readonly StatCollection          $defenderStats,
    )
    {
    }

    public function getAttacker(): CombatOpponentInterface
    {
        return $this->attacker;
    }

    public function getDefender(): CombatOpponentInterface
    {
        return $this->defender;
    }

    public function getDamage(): float
    {
        return $this->damage;
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