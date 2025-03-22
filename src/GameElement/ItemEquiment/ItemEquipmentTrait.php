<?php

namespace App\GameElement\ItemEquiment;

use App\GameElement\Combat\Stats\AbstractStat;

trait ItemEquipmentTrait
{
    /** @var AbstractStat[] */
    protected readonly array $combatStatModifiers;
    protected readonly float $maxCondition;


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