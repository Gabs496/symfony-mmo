<?php

namespace App\GameElement\Combat\Phase;

use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;

class Attack
{
    public function __construct(
        protected readonly GameObjectInterface         $attacker,
        protected StatCollection                       $statCollection,
    )
    {
    }

    public function getAttacker(): GameObjectInterface
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