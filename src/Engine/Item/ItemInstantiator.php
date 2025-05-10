<?php

namespace App\Engine\Item;

use App\Entity\Data\ItemInstance;
use App\GameElement\Item\AbstractItemPrototype;

readonly class ItemInstantiator
{
    public function createFrom(AbstractItemPrototype $itemPrototype, int $quantity): ItemInstance
    {
        $instance = new ItemInstance();
        $instance
            ->setItemPrototypeId($itemPrototype->getId())
            ->setItemPrototype($itemPrototype)
            ->setComponents($itemPrototype->getComponents())
            ->setQuantity($quantity)
        ;

        return $instance;
    }
}