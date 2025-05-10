<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;

interface ItemInstanceInterface extends GameComponentOwnerInterface
{
    public function isInstanceOf(AbstractItemPrototype $item): bool;

    public function getItemPrototype(): AbstractItemPrototype;

    public function getQuantity(): int;

    public function merge(ItemInstanceInterface $itemInstance): void;
}