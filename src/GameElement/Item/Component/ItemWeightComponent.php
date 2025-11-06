<?php

namespace App\GameElement\Item\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

class ItemWeightComponent implements GameComponentInterface
{
    public function __construct(
        protected float $weight = 0.0,
    )
    {
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public static function getId(): string
    {
        return 'item_weight_component';
    }
}