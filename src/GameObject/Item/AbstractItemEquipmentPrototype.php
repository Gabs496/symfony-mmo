<?php

namespace App\GameObject\Item;

use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;

abstract class AbstractItemEquipmentPrototype extends AbstractBaseItemPrototype
{
    public function __construct(
        string $id,
        string $name,
        string $description,
        float $maxCondition,
        float $weight,
        array $combatStatModifiers,
        array $components = [],
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            stackable: false,
            weight: $weight,
            components: array_merge(
                $components,
                [
                    new ItemEquipmentComponent($combatStatModifiers, $maxCondition),
                ]
            )
        );
    }
}