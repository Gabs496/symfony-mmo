<?php

namespace App\GameElement\Gathering\Reward;

use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Reward\RewardInterface;

readonly class ItemReward implements RewardInterface
{
    public function __construct(
        private AbstractItemPrototype $item,
        private int                   $quantity = 1,
        private array                 $attributes = []
    )
    {
    }

    public function getItem(): AbstractItemPrototype
    {
        return $this->item;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getName(): string
    {
        return $this->item->getName();
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}