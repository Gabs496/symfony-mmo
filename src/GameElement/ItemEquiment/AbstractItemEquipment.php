<?php

namespace App\GameElement\ItemEquiment;

use App\GameElement\Combat\Stats\AbstractStat;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AvailableAction\Drop;
use App\GameElement\ItemEquiment\AvailableAction\Equip;

readonly abstract class AbstractItemEquipment extends AbstractItem
{
    public function __construct(
        string          $id,
        string          $name,
        string          $description,
        float           $weight,
        /** @var AbstractStat[] */
        protected array $combatStatModifiers,
        protected float $maxCondition,
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            stackable: false,
            weight: $weight,
            availableActions: [
                new Drop(),
                new Equip(),
            ]
        );
    }

    /** @return AbstractStat[] */
    public function getCombatStatModifiers(): array
    {
        return $this->combatStatModifiers;
    }

    public function getMaxCondition(): float
    {
        return $this->maxCondition;
    }
}