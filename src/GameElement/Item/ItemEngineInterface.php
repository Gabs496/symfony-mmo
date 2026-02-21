<?php

namespace App\GameElement\Item;

use App\GameElement\Item\Event\ItemExtractedEvent;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use PennyPHP\Core\Entity\GameObject;

interface ItemEngineInterface
{
    public function give(GameObject $to, GameObject $item): void;

    /**
     * @return array<ItemExtractedEvent>
     * @throws ItemQuantityNotAvailableException
     */
    public function take(GameObject $player, string $type, int $quantity): array;
}