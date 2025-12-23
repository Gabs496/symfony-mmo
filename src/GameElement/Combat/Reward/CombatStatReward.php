<?php

namespace App\GameElement\Combat\Reward;

use App\GameElement\Reward\RewardInterface;

readonly class CombatStatReward implements RewardInterface
{
    public function __construct(
        protected string $statClass,
        protected float $amount,
    ) {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getStatClass(): string
    {
        return $this->statClass;
    }

    public function getAttributes(): array
    {
        return [];
    }
}