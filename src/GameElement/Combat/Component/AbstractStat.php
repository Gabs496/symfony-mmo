<?php

namespace App\GameElement\Combat\Component;

abstract class AbstractStat
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

    public function increase(float $amount): void
    {
        $this->value += $amount;
    }

    public abstract static function getLabel(): string;
}