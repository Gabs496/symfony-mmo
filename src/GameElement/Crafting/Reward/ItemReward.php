<?php

namespace App\GameElement\Crafting\Reward;

use App\GameElement\Reward\RewardInterface;

readonly class ItemReward implements RewardInterface
{
    public function __construct(
        private string  $itemId,
        private int $quantity = 1
    )
    {
    }

    public function getItemId(): string
    {
        return $this->itemId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}