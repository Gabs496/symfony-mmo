<?php

namespace App\Entity\Data;

use Doctrine\ORM\Mapping\Entity;

#[Entity]
class EquippedItemBag extends ItemBag
{
    public function __construct(PlayerCharacter $player)
    {
        parent::__construct($player, 1.0);
    }

    /**
     * Equipment bag does not have max size
     */
    public function getOccupedSpace(): float
    {
        return (float)$this->items->reduce(function(int $carry, ItemInstance $item) {
            return $carry + $item->getQuantity();
        }, 0);
    }

    public function isFull(): bool
    {
        //TODO: change this logic. Equipment bag should match a specific set of equipment slots
        return $this->items->count() >= 1;
    }
}