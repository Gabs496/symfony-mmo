<?php

namespace App\GameElement\Item\Event;

use App\GameElement\Item\Component\ItemBagComponent;

readonly class ItemBagUpdate
{
    public function __construct(
        private  ItemBagComponent $bag,
    )
    {
    }

    public function getBag(): ItemBagComponent
    {
        return $this->bag;
    }
}