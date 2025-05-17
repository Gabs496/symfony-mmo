<?php

namespace App\GameElement\Combat\Component;

use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\StatCollection;

class Attack
{
    public function __construct(
        protected CombatOpponentInterface $attacker,
        protected StatCollection $statCollection
    )
    {
    }

    public function getAttacker(): CombatOpponentInterface
    {
        return $this->attacker;
    }

    public function getStatCollection(): StatCollection
    {
        return $this->statCollection;
    }
}