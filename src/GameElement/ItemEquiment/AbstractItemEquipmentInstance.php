<?php

namespace App\GameElement\ItemEquiment;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\ItemInstanceTrait;

trait AbstractItemEquipmentInstance
{
    use ItemInstanceTrait;

    /** @var AbstractItemEquipment */
    protected AbstractItem $item;

    public abstract function getCombatStatModifiers(): array;
}