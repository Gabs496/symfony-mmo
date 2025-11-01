<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameObject\GameObjectInterface;

interface ItemInstanceInterface extends GameObjectInterface
{
    public function isInstanceOf(AbstractItemPrototype $item): bool;

    public function getPrototype(): AbstractItemPrototype;

    public function getQuantity(): int;

    public function merge(ItemInstanceInterface $itemInstance): void;
}