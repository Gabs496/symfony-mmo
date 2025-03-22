<?php

namespace App\Entity\Data;

use App\GameElement\ItemEquiment\ItemEquipmentInstanceInterface;
use Doctrine\ORM\Mapping\Entity;

/**
 * @method ItemEquipmentInstanceInterface[] getItems()
 */
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
        return 0.0;
    }

    public function isFull(): bool
    {
        //TODO: change this logic. Equipment bag should match a specific set of equipment slots
        return $this->items->count() >= 1;
    }
}