<?php

namespace App\GameElement\Item\Reward;

use App\GameElement\Reward\RewardInterface;
use PennyPHP\Core\GameObjectInterface;

readonly class ItemReward implements RewardInterface
{
    public function __construct(
        private GameObjectInterface $item,
        private int                 $quantity = 1,
        private array               $attributes = [],
    )
    {

    }

    public function getItem(): GameObjectInterface
    {
        return $this->item;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}