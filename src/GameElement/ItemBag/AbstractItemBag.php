<?php

namespace App\GameElement\ItemBag;

use App\GameElement\Item\AbstractItemInstance;
use App\GameElement\ItemBag\Exception\MaxSizeReachedException;

abstract class AbstractItemBag
{
    /** @var AbstractItemInstance[] $items */
    protected iterable $items;

    protected function __construct(
        protected float $size
    )
    {
        $this->items = [];
    }

    /**
     * @throws MaxSizeReachedException
     */
    public function addItem(AbstractItemInstance $itemInstance): void
    {
        if ($this->isFull()) {
            throw new MaxSizeReachedException();
        }

        $item = $itemInstance->getItem();
        if ($item->isStackable()) {
            foreach ($this->getItems() as $existingItem) {
                if ($existingItem->isInstanceOf($item)) {
                    $existingItem->addQuantity($itemInstance->getQuantity());
                    return;
                }
            }
        }

        $this->items[] = $itemInstance;
    }

    public function removeItem(AbstractItemInstance $itemInstance): void
    {
        $key = array_search($itemInstance, $this->items, true);
        if ($key !== false) {
            unset($this->items[$key]);
        }
    }

    public function getItems(): iterable
    {
        return $this->items;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getOccupedSpace(): float
    {
        $items = iterator_to_array($this->items);
        return array_reduce($items,
            fn($carry, AbstractItemInstance $instance)
                => (float)bcadd($carry, bcmul($instance->getItem()->getWeight(), $instance->getQuantity(), 4), 4),
            0.0
        );
    }

    public function getFullness(): float
    {
        return (float)bcdiv($this->getOccupedSpace(), $this->size, 4);
    }

    public function isFull(): bool
    {
        return bccomp($this->getOccupedSpace(), $this->size, 4) >= 0;
    }
}