<?php

namespace App\GameObject\Item;

use App\GameElement\ItemEquiment\ItemEquipmentInterface;
use App\GameElement\ItemEquiment\ItemEquipmentTrait;

readonly class AbstractBaseItemEquipment extends AbstractBaseItem implements ItemEquipmentInterface
{
    use ItemEquipmentTrait;

    public function __construct(
        string $id,
        string $name,
        string $description,
        float $maxCondition,
        float $weight,
        array $combatStatModifiers,
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            stackable: false,
            weight: $weight,
        );
        $this->combatStatModifiers = $combatStatModifiers;
        $this->maxCondition = $maxCondition;
    }
}