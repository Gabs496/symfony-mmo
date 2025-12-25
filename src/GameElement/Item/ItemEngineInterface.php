<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;

interface ItemEngineInterface
{
    public function give(GameObjectInterface $to, GameObjectInterface $item): void;

    /** @throws ItemQuantityNotAvailableException */
    public function take(GameObjectInterface $from, GameObjectInterface $item, int $quantity): GameObjectInterface;
}