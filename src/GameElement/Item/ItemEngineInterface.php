<?php

namespace App\GameElement\Item;

use PennyPHP\Core\GameObject\Entity\GameObject;
use PennyPHP\Core\GameObject\GameObjectInterface;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;

interface ItemEngineInterface
{
    public function give(GameObject $to, GameObject $item): void;

    /** @throws ItemQuantityNotAvailableException */
    public function take(GameObject $player, string $type, int $quantity): array;
}