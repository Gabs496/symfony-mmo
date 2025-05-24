<?php

namespace App\GameElement\Drop\Component;

readonly class Drop
{
    public function __construct(
        protected float $rate,
    )
    {

    }

    public function getRate(): float
    {
        return $this->rate;
    }

}