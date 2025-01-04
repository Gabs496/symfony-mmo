<?php

namespace App\GameElement\ItemBag;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AbstractItemInstance;
use App\GameElement\ItemBag\Exception\ItemQuantityNotAvailableException;
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

    /**
     * @throws ItemQuantityNotAvailableException
     */
    public function extract(AbstractItem $item, int $quantity): AbstractItemInstance
    {
        foreach ($this->items as $itemInstance) {
            if ($itemInstance->isInstanceOf($item) && $itemInstance->getQuantity() >= $quantity) {
                $itemInstance->setQuantity($itemInstance->getQuantity() - $quantity);
                $extracted = clone $itemInstance;
                $extracted->setQuantity($quantity);
                if ($itemInstance->getQuantity() <= 0) {
                    unset($itemInstance);
                }
                return $extracted;
            }
        }

        throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $item->getName(), $quantity));
    }

    public function has(AbstractItem $item, int $quantity = 1): bool
    {
        foreach ($this->items as $itemInstance) {
            if ($itemInstance->isInstanceOf($item) && $itemInstance->getQuantity() >= $quantity) {
                return true;
            }
        }

        return false;
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