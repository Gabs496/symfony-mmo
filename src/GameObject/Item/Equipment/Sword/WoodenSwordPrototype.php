<?php

namespace App\GameObject\Item\Equipment\Sword;

use App\Entity\Core\GameObject;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameObject\Item\AbstractItemEquipmentPrototype;

class WoodenSwordPrototype extends AbstractItemEquipmentPrototype
{
    public const string ID = "EQUIP_SWORD_WOODEN";
    public function make(
        array $components = [],
        string $name = 'Wooden Sword',
        string $description = 'A simple sword made of chestnut wood.',
        float $weight = 0.2,
        float $maxCondition = 0.5,
        array $combatStatModifiers = [
            PhysicalAttackStat::class => new PhysicalAttackStat(0.05)
        ],
    ): GameObject
    {
        return parent::make(
            components: $components,
            name: $name,
            description: $description,
            weight: $weight,
            maxCondition: $maxCondition,
            combatStatModifiers: $combatStatModifiers,
        );
    }

    public static function getId(): string
    {
        return self::ID;
    }
}