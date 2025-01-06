<?php

namespace App\GameElement\Crafting\Reward;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Reward\RewardInterface;

readonly class ItemReward implements RewardInterface
{
    public function __construct(
        private AbstractItem $item,
        private int          $quantity = 1
    )
    {
    }

    public function getItem(): AbstractItem
    {
        return $this->item;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}