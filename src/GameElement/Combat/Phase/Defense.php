<?php

namespace App\GameElement\Combat\Phase;

use App\GameElement\Combat\HasCombatComponentInterface;
use App\GameElement\Combat\StatCollection;

class Defense
{
    public function __construct(
        protected HasCombatComponentInterface $defender,
        protected StatCollection              $statCollection
    )
    {
    }

    public function getDefender(): HasCombatComponentInterface
    {
        return $this->defender;
    }

    public function getStatCollection(): StatCollection
    {
        return $this->statCollection;
    }
}