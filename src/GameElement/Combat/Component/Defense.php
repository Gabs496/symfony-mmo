<?php

namespace App\GameElement\Combat\Component;

use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\StatCollection;

class Defense
{
    public function __construct(
        protected CombatOpponentTokenInterface $defender,
        protected StatCollection          $statCollection
    )
    {
    }

    public function getDefender(): CombatOpponentTokenInterface
    {
        return $this->defender;
    }

    public function getStatCollection(): StatCollection
    {
        return $this->statCollection;
    }
}