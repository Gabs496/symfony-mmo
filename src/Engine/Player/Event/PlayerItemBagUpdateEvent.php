<?php

namespace App\Engine\Player\Event;

use App\Entity\Item\ItemBag;

readonly class PlayerItemBagUpdateEvent
{
    public function __construct(
        private string   $playerId,
        private  ItemBag $itemBag,
    )
    {
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function getItemBag(): ItemBag
    {
        return $this->itemBag;
    }
}