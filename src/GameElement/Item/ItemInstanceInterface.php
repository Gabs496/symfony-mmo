<?php

namespace App\GameElement\Item;

interface ItemInstanceInterface
{
    public function isInstanceOf(AbstractItem $item): bool;

    public function getItem(): AbstractItem;

    public function getQuantity(): int;

    public static function createFrom(AbstractItem $item, int $quantity = 1): self;

    public function merge(ItemInstanceInterface $itemInstance): void;
}