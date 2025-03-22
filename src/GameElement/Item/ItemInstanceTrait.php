<?php

namespace App\GameElement\Item;

use RuntimeException;

/**
 * @method string getName()
 * @method string getDescription()
 * @method bool isStackable()
 * @method float getWeight()
 */
trait ItemInstanceTrait
{
    protected int $quantity = 1;

    public function __construct(
        // TODO: try to make it readonly
        protected AbstractItem $item,
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

    public static function createFrom(AbstractItem $item, int $quantity = 1): self
    {
        $currentClass = static::class;
        return (new $currentClass($item))
            ->setQuantity($quantity)
        ;
    }

    public function merge(ItemInstanceInterface $itemInstance): void
    {
        if (!$this->isInstanceOf($itemInstance->getItem())) {
            throw new RuntimeException(sprintf('Cannot merge different items: "%s" and "%s"', $this->getItem()::class, $itemInstance->getItem()::class));
        }

        $this->quantity += $itemInstance->getQuantity();
        unset($itemInstance);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->item->$name(...$arguments);
    }
}