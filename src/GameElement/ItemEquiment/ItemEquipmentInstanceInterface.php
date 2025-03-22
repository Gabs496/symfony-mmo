<?php

namespace App\GameElement\ItemEquiment;

use App\GameElement\Combat\Stats\AbstractStat;

interface ItemEquipmentInstanceInterface
{
    /**
     * @return AbstractStat[]
     */
    public function getCombatStatModifiers(): array;
}