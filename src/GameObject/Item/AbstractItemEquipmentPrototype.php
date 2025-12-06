<?php

namespace App\GameObject\Item;

use App\Entity\Core\GameObject;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;

abstract class AbstractItemEquipmentPrototype extends AbstractBaseItemPrototype
{
    public function make(
        array $components = [],
        string $name = 'Item Equipment',
        string $description = '',
        float $weight = 1.0,
        float $maxCondition = 1.0,
        array $combatStatModifiers = [],
    ): GameObject
    {
        $gameObject = parent::make(
            components: $components,
            name: $name,
            description: $description,
            weight: $weight,
        );
        $gameObject
            ->setComponent(new ItemEquipmentComponent($combatStatModifiers, $maxCondition))
            ->setComponent(new StackComponent(1, 1))
        ;
        return $gameObject;
    }
}