<?php

namespace App\GameObject\Item\Equipment;

use App\GameElement\Item\ItemExtension\ConsumableItemInterface;
use App\GameElement\ItemEquiment\AbstractItemEquipment;

abstract readonly class AbstractBaseEquipment extends AbstractItemEquipment implements ConsumableItemInterface
{
    public function __construct(
        string $id,
        string $name,
        string $description,
        protected float $maxCondition,
        float $weight = 100.0,
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            stackable: true,
            weight: $weight,
        );
    }

    public function getMaxCondition(): float
    {
        return $this->maxCondition;
    }
}