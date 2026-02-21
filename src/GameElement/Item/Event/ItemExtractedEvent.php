<?php

namespace App\GameElement\Item\Event;

use App\GameElement\Item\Component\ItemComponent;

readonly class ItemExtractedEvent
{
    public function __construct(
        private ItemComponent $item,
        private int           $quantity,
    )
    {

    }

    public function getItem(): ItemComponent
    {
        return $this->item;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}