<?php

namespace App\GameElement\Item;

use App\Entity\Item\ItemObject;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Render\Component\RenderComponent;

abstract class AbstractItemBag
{
    /** @var ItemObject[] $items */
    protected iterable $items = [];

    protected function __construct(
        protected float $size
    )
    {
    }

    /**
     * @throws MaxBagSizeReachedException
     */
    public function addItem(GameObjectInterface $itemObject): void
    {
        if ($this->isFull()) {
            throw new MaxBagSizeReachedException();
        }

        $item = $itemObject->getComponent(ItemComponent::class);
        foreach ($this->getItems() as $existingItemObject) {
            $existingItem = $existingItemObject->getGameObject();
            $existingItemComponent = $existingItem->getComponent(ItemComponent::class);
            if ($existingItem->isInstanceOf($itemObject) && !$existingItemComponent->isStackFull()) {
                $existingItemComponent->increaseBy($item->getQuantity());
                return;
            }
        }

        $this->items[] = new ItemObject($itemObject, $this);
    }

    public function findAndExtract(GameObjectPrototypeInterface $prototype, int $quantity = 1): GameObjectInterface
    {
        if (!$this->has($prototype, $quantity)) {
            throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $prototype->getComponent(RenderComponent::class)->getName(), $quantity));
        }

        $newInstance = null;
        $quantity = 0;
        foreach ($this->items as $itemObject) {
            $item = $itemObject->getGameObject();
            if ($item->getPrototype() === $prototype) {
                $extractedInstance = $this->extract($item, $quantity);
                if (!$newInstance) {
                    $newInstance = $extractedInstance;
                }
                $quantity+= $extractedInstance->getComponent(ItemComponent::class)->getOrginalAvailability()();
            }
        }

        return $newInstance;
    }

    /**
     * @throws ItemQuantityNotAvailableException
     */
    public function extract(GameObjectInterface $item, int $quantity = 0): GameObjectInterface
    {
        foreach ($this->items as $key => $itemObjecInBag) {
            if ($itemObjecInBag->getGameObject()->getId() === $item->getId()) {
                $itemComponent = $item->getComponent(ItemComponent::class);
                if ($itemComponent->getQuantity() === $quantity) {
                    unset($this->items[$key]);
                    return $itemObjecInBag->getGameObject();
                }
                if ($itemComponent->getQuantity() < $quantity) {
                    throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $item->getComponent(RenderComponent::class)->getName(), $quantity));
                }
                $itemComponent->decreaseBy($quantity);
                $newGameObject = clone $item;
                $extractedItem = $newGameObject->getComponent(ItemComponent::class);
                $extractedItem->setQuantity($quantity);
                return $newGameObject;
            }
        }

        throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $item->getComponent(RenderComponent::class)->getName(), $quantity));
    }

    public function has(GameObjectPrototypeInterface $item, int $quantity = 1): bool
    {
        return $this->getQuantity($item) >= $quantity;
    }

    public function getQuantity(GameObjectPrototypeInterface $item): int
    {
        $instances = $this->find($item);
        return array_reduce($instances, fn($carry, ItemObject $instance)
                => $carry + $instance->getGameObject()->getComponent(ItemComponent::class)->getQuantity(), 0
        );
    }

    /** @return ItemObject[] */
    public function find(GameObjectPrototypeInterface $item): array
    {
        $itemObjects = [];
        foreach ($this->items as $itemObject) {
            if ($itemObject->getGameObject()->isInstanceOf($item)) {
                $itemObjects[] = $itemObject;
            }
        }
        return $itemObjects;
    }

    /** @return ItemObject[] */
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
            fn($carry, ItemObject $itemObject)
                => (float)bcadd($carry, bcmul($itemObject->getGameObject()->getComponent(ItemComponent::class)->getWeight(), $itemObject->getGameObject()->getComponent(ItemComponent::class)->getQuantity(), 2), 2),
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