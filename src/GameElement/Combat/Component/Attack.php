<?php

namespace App\GameElement\Combat\Component;

use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\StatCollection;

class Attack
{
    public function __construct(
        protected readonly CombatOpponentTokenInterface $attacker,
        protected StatCollection $statCollection,
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

    public function setStatCollection(StatCollection $statCollection): void
    {
        $this->statCollection = $statCollection;
    }
}