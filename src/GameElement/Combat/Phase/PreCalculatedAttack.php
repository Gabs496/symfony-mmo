<?php

namespace App\GameElement\Combat\Phase;

use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\StatCollection;

readonly class PreCalculatedAttack
{
    public function __construct(
        protected CombatOpponentTokenInterface $attacker,
        protected StatCollection               $statCollection,
    )
    {
    }

    public function getAttacker(): CombatOpponentTokenInterface
    {
        return $this->attacker;
    }

    public function getStatCollection(): StatCollection
    {
        return $this->statCollection;
    }
}