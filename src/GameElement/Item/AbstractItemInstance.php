<?php

namespace App\GameElement\Item;

abstract class AbstractItemInstance
{
    private int $quantity = 1;
    private float $wear = 1.0;

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

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    public function getWear(): float
    {
        return $this->wear;
    }

    public function setWear(float $wear)
    {
        $this->wear = $wear;
    }
}