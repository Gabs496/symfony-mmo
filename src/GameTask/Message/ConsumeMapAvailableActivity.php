<?php

namespace App\GameTask\Message;

use App\Interface\ActivityEventInterface;

class ConsumeMapAvailableActivity implements ActivityEventInterface
{
    public function __construct(
        private readonly string $mapAvailableActivityId,
        private readonly float $quantity = 1.0
    )
    {
    }

    public function getMapAvailableActivityId(): string
    {
        return $this->mapAvailableActivityId;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }
}