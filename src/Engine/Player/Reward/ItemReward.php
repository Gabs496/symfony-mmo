<?php

namespace App\Engine\Player\Reward;

use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Reward\RewardInterface;
use App\GameElement\Reward\RewardNotificationInterface;

readonly class ItemReward implements RewardInterface, RewardNotificationInterface
{
    public function __construct(
        private AbstractItemPrototype $item,
        private int                   $quantity = 1
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
}