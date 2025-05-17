<?php

namespace App\GameElement\Combat\Component;

use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\StatCollection;

class Defense
{
    public function __construct(
        protected CombatOpponentInterface $defender,
        protected StatCollection          $statCollection
    )
    {
    }

    public function getDefender(): CombatOpponentInterface
    {
        return $this->defender;
    }

    public function getStatCollection(): StatCollection
    {
        return $this->statCollection;
    }
}