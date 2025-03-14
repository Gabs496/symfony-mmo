<?php

namespace App\GameElement\Combat\Stats;

abstract readonly class BaseStat
{
    public function __construct(
        private float $value,
    )
    {
    }

    public function getValue(): float
    {
        return $this->value;
    }
}