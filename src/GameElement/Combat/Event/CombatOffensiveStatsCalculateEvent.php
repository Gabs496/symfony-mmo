<?php

namespace App\GameElement\Combat\Event;

use App\GameElement\Combat\CombatOpponentInterface;

class CombatOffensiveStatsCalculateEvent extends CombatCalculateStatsEvent
{
    public function __construct(
        private readonly CombatOpponentInterface $attacker,
        private readonly CombatOpponentInterface $defender,
    )
    {
        parent::__construct();
    }

    public function getAttacker(): CombatOpponentInterface
    {
        return $this->attacker;
    }

    public function getDefender(): CombatOpponentInterface
    {
        return $this->defender;
    }
}