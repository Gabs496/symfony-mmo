<?php

namespace App\GameElement\ItemEquiment;

use App\GameElement\Item\AbstractItem;

readonly abstract class AbstractItemEquipment extends AbstractItem
{
    use ItemEquipmentTrait;

    public function __construct(
        string          $id,
        string          $name,
        string          $description,
        array $combatStatModifiers,
        float $maxCondition,
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            stackable: false,
        );
        $this->combatStatModifiers = $combatStatModifiers;
        $this->maxCondition = $maxCondition;
    }


}