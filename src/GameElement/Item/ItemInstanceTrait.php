<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameObject\GameObjectTrait;
use RuntimeException;

trait ItemInstanceTrait
{
    use GameObjectTrait;
    protected ?AbstractItemPrototype $itemPrototype = null;
    protected int $quantity = 1;

    public function isInstanceOf(AbstractItemPrototype $item): bool
    {
        return $this->itemPrototype::class === $item::class;
    }

    public function getItemPrototype(): AbstractItemPrototype
    {
        return $this->itemPrototype;
    }

    public function setItemPrototype(?AbstractItemPrototype $itemPrototype): self
    {
        $this->itemPrototype = $itemPrototype;

        return $this;
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

    public function merge(ItemInstanceInterface $itemInstance): void
    {
        if (!$this->isInstanceOf($itemInstance->getItemPrototype())) {
            throw new RuntimeException(sprintf('Cannot merge different items: "%s" and "%s"', $this->getItemPrototype()::class, $itemInstance->getItemPrototype()::class));
        }

        $this->quantity += $itemInstance->getQuantity();
        unset($itemInstance);
    }
}