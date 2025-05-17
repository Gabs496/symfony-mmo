<?php

namespace App\GameElement\Item;

interface ItemInstanceInterface
{
    public function isInstanceOf(AbstractItemPrototype $item): bool;

    public function getItemPrototype(): AbstractItemPrototype;

    public function getQuantity(): int;

    public function merge(ItemInstanceInterface $itemInstance): void;
}