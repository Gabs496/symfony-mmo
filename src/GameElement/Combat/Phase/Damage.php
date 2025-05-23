<?php

namespace App\GameElement\Combat\Phase;

class Damage
{
    public function __construct(
        protected float $value = 0.0,
    )
    {
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function increaseValue(float $value): void
    {
        $this->value += $value;
    }
}