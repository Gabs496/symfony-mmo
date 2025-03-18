<?php

namespace App\GameElement\Item;

abstract class AbstractItemInstance
{
    protected int $quantity = 1;
    protected float $wear = 1.0;
    protected ?AbstractItemBag $bag;

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

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getWear(): float
    {
        return $this->wear;
    }

    public function setWear(float $wear): self
    {
        $this->wear = $wear;
        return $this;
    }

    public function getBag(): AbstractItemBag
    {
        return $this->bag;
    }

    public function setBag(?AbstractItemBag $bag): static
    {
        $this->bag = $bag;
        return $this;
    }

    public static function createFrom(AbstractItem $item, int $quantity = 1): AbstractItemInstance
    {
        $currentClass = static::class;
        return (new $currentClass($item))
            ->setQuantity($quantity)
            ->setWear(1.0)
        ;
    }
}