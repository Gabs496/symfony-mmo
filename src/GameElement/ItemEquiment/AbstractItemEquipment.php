<?php

namespace App\GameElement\ItemEquiment;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AvailableAction\Drop;
use App\GameElement\ItemEquiment\AvailableAction\Equip;

readonly abstract class AbstractItemEquipment extends AbstractItem
{
    public abstract function getCombatStatModifiers(): array;

    public function getAvailableActions(): array
    {
        return [
            new Drop(),
            new Equip(),
        ];
    }
}