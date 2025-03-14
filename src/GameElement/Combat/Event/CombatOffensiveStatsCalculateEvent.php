<?php

namespace App\GameElement\Combat\Event;

class CombatOffensiveStatsCalculateEvent extends CombatCalculateStatsEvent
{
    public function __construct(
        private readonly object $attacker,
        private readonly object $defender,
    )
    {
        parent::__construct();
    }

    public function getAttacker(): object
    {
        return $this->attacker;
    }

    public function getDefender(): object
    {
        return $this->defender;
    }
}