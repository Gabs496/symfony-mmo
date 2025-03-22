<?php

namespace App\GameElement\ItemWeight;

trait ItemWithWeightTrait
{
    protected readonly float $weight;

    public function getWeight(): float
    {
        return $this->weight;
    }
}