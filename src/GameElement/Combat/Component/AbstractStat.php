<?php

namespace App\GameElement\Combat\Component;

abstract readonly class AbstractStat
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