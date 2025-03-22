<?php

namespace App\GameElement\Item;

use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;

abstract class AbstractItemBag
{
    /** @var ItemInstanceInterface[] $items */
    protected iterable $items;

    protected function __construct(
        protected float $size
    )
    {
        $this->items = [];
    }

    /**
     * @throws MaxBagSizeReachedException
     */
    public function addItem(ItemInstanceInterface $itemInstance): void
    {
        if ($this->isFull()) {
            throw new MaxBagSizeReachedException();
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

    public function findAndExtract(AbstractItem $item, int $quantity = 1): ItemInstanceInterface
    {
        if (!$this->has($item, $quantity)) {
            throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $item->getName(), $quantity));
        }

        $newInstance = null;
        foreach ($this->items as $itemInstance) {
            if ($itemInstance->isInstanceOf($item)) {
                $extractedInstance = $this->extract($itemInstance, $quantity);
                if ($newInstance) {
                    $newInstance->merge($extractedInstance);
                } else {
                    $newInstance = $extractedInstance;
                }
            }
        }

        return $newInstance;
    }

    /**
     * @throws ItemQuantityNotAvailableException
     */
    public function extract(ItemInstanceInterface $itemInstance, int $quantity = 0): ItemInstanceInterface
    {
        foreach ($this->items as $key => $itemInstanceInBag) {
            if ($itemInstanceInBag == $itemInstance) {
                $itemInstanceInBag->setQuantity($itemInstanceInBag->getQuantity() - $quantity);
                $extracted = clone $itemInstanceInBag;
                $extracted->setQuantity($quantity);
                if ($itemInstanceInBag->getQuantity() <= 0) {
                    unset($this->items[$key]);
                }
                return $extracted;
            }
        }

        throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $itemInstance->getItem()->getName(), $quantity));
    }

    public function has(AbstractItem $item, int $quantity = 1): bool
    {
        return $this->getQuantity($item) >= $quantity;
    }

    public function getQuantity(AbstractItem $item): int
    {
        return array_reduce((array)$this->find($item), fn($carry, $instance)
                => $carry + $instance->getQuantity(),
            0
        );
    }

    /** @return ItemInstanceInterface[] */
    public function find(AbstractItem $item): iterable
    {
        foreach ($this->items as $itemInstance) {
            if ($itemInstance->isInstanceOf($item)) {
                yield $itemInstance;
            }
        }
    }

    /** @return ItemInstanceInterface[] */
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
            fn($carry, $instance)
                => (float)bcadd($carry, bcmul($instance->getItem()->getWeight(), $instance->getQuantity(), 2), 2),
            0.0
        );
    }

    public function getFullness(): float
    {
        return (float)bcdiv($this->getOccupedSpace(), $this->size, 2);
    }

    public function isFull(): bool
    {
        return bccomp($this->getOccupedSpace(), $this->size, 2) >= 0;
    }
}