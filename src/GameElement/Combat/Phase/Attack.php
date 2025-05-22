<?php

namespace App\GameElement\Combat\Phase;

use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\StatCollection;

class Attack
{
    public function __construct(
        protected readonly CombatOpponentInterface $attacker,
        protected StatCollection $statCollection,
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

    public function setStatCollection(StatCollection $statCollection): void
    {
        $this->statCollection = $statCollection;
    }
}