<?php

namespace App\GameElement\Item;

use App\Entity\Data\ItemObject;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObject\GameObjectPrototypeInterface;
use App\GameElement\Item\Component\ItemWeightComponent;
use App\GameElement\Item\Component\StackComponent;
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

        $stack = $itemObject->getComponent(StackComponent::class);
        foreach ($this->getItems() as $existingItemObject) {
            $existingItem = $existingItemObject->getGameObject();
            $existingStack = $existingItem->getComponent(StackComponent::class);
            if ($existingItem->isInstanceOf($itemObject) && !$existingStack->isFull()) {
                $existingStack->increaseBy($stack->getCurrentQuantity());
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
        $stack = new StackComponent();
        foreach ($this->items as $itemObject) {
            $item = $itemObject->getGameObject();
            if ($item->getPrototype()->getId() === $prototype->getId()) {
                $extractedInstance = $this->extract($itemObject, $quantity);
                if (!$newInstance) {
                    $newInstance = $extractedInstance;
                }
                $stack->increaseBy($extractedInstance->getComponent(StackComponent::class)->getCurrentQuantity());
            }
        }

        $newInstance->setComponent(StackComponent::class, $stack);
        return $newInstance;
    }

    /**
     * @throws ItemQuantityNotAvailableException
     */
    public function extract(GameObjectInterface $item, int $quantity = 0): GameObjectInterface
    {
        foreach ($this->items as $key => $itemObjecInBag) {
            if ($itemObjecInBag->getGameObject()->getId() === $item->getId()) {
                $stack = $item->getComponent(StackComponent::class);
                if ($stack->getCurrentQuantity() === $quantity) {
                    unset($this->items[$key]);
                    return $itemObjecInBag->getGameObject();
                }
                if ($stack->getCurrentQuantity() < $quantity) {
                    throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $item->getComponent(RenderComponent::class)->getName(), $quantity));
                }
                $stack->decreaseBy($quantity);
                $newGameObject = clone $item;
                $extractedStack = $newGameObject->getComponent(StackComponent::class);
                $extractedStack->setCurrentQuantity($quantity);
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
        return array_reduce($instances, fn($carry, $instance)
                => $carry + $instance->getQuantity(), 0
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
                => (float)bcadd($carry, bcmul($itemObject->getGameObject()->getComponent(ItemWeightComponent::class)->getWeight(), $itemObject->getGameObject()->getComponent(StackComponent::class)->getCurrentQuantity(), 2), 2),
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