<?php

namespace App\GameElement\Item\Reward;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Reward\RewardInterface;
use App\GameElement\Reward\RewardNotificationInterface;

readonly class ItemReward implements RewardInterface, RewardNotificationInterface
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

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getName(): string
    {
        return $this->item->getName();
    }
}