<?php

namespace App\GameElement\Healing\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

class HealingComponent implements GameComponentInterface
{
    public function __construct(
        protected float $amount,
    ) {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public static function getId(): string
    {
        return 'healing_component';
    }
}