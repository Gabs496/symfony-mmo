<?php

namespace App\GameElement\Item;

interface ItemInstanceInterface
{
    public function isInstanceOf(AbstractItem $item): bool;

    public function getItem(): AbstractItem;

    public function getQuantity(): int;

    public function merge(ItemInstanceInterface $itemInstance): void;
}