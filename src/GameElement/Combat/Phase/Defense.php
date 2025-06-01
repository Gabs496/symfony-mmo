<?php

namespace App\GameElement\Combat\Phase;

use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;

class Defense
{
    public function __construct(
        protected GameObjectInterface $defender,
        protected StatCollection              $statCollection
    )
    {
    }

    public function getDefender(): GameObjectInterface
    {
        return $this->defender;
    }

    public function getStatCollection(): StatCollection
    {
        return $this->statCollection;
    }
}