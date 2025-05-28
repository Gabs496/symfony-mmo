<?php

namespace App\GameObject\Item\Equipment\Sword;

use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameObject\Item\AbstractItemEquipmentPrototype;

class WoodenSwordPrototype extends AbstractItemEquipmentPrototype
{
    public const string ID = "EQUIP_SWORD_WOODEN";
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Wooden Sword',
            description: 'A simple sword made of chestnut wood.',
            maxCondition: 0.5,
            weight: 0.2,
            combatStatModifiers: [
                PhysicalAttackStat::class => new PhysicalAttackStat(0.05),
            ]
        );
    }
}