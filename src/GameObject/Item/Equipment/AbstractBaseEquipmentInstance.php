<?php

namespace App\GameObject\Item\Equipment;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AbstractItemInstance;

abstract class AbstractBaseEquipmentInstance extends AbstractItemInstance
{

    /** @var AbstractBaseEquipment */
    protected readonly AbstractItem $item;

    public function getCombatStatModifiers(): array
    {
        return $this->item->getCombatStatModifiers();
    }
}