<?php

namespace App\GameElement\Item;

abstract class AbstractItemInstance
{
    public function __construct(
        protected readonly AbstractItem $item,
    )
    {
    }
    public function isInstanceOf(AbstractItem $item): bool
    {
        return $this->item->getId() === $item->getId();
    }

    public function getItem(): AbstractItem
    {
        return $this->item;
    }
}