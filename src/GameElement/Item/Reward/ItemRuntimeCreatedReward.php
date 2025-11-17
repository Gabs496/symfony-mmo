<?php

namespace App\GameElement\Item\Reward;

use App\GameElement\Reward\RewardInterface;

readonly class ItemRuntimeCreatedReward implements RewardInterface
{
    public function __construct(
        private string $itemPrototypeId,
        private int    $quantity = 1,
        private array  $attributes = []
    )
    {
    }

    public function getItemPrototypeId(): string
    {
        return $this->itemPrototypeId;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}