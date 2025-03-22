<?php

namespace App\GameElement\ItemEquiment;

use App\GameElement\Combat\Stats\AbstractStat;

interface ItemEquipmentInterface
{
    /** @return AbstractStat[] */
    public function getCombatStatModifiers(): array;

    public function getMaxCondition(): float;
}