<?php

namespace App\GameObject\Item;

use App\GameElement\Item\Component\StackComponent;
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
            weight: $weight,
            components: array_merge([
                    new ItemEquipmentComponent($combatStatModifiers, $maxCondition),
                    new StackComponent(1, 1),
                ], $components
            )
        );
    }
}