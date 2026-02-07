<?php

namespace App\GameObject\Item\Equipment\Sword;

use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Equipment\Component\EquipmentComponent;
use App\GameElement\Render\Component\RenderComponent;

#[RenderComponent(
    name: 'Wooden Sword',
    description: 'A simple sword made of chestnut wood.',
    iconPath: '/items/equip_sword_wooden.png'
)]
#[ItemComponent(weight: 0.2)]
#[EquipmentComponent(
    targetSlot: 'right_arm',
    stats: [
        new PhysicalAttackStat(0.05)
    ]
)]
class WoodenSwordPrototype extends AbstractGameObjectPrototype
{
    public const string ID = "EQUIP_SWORD_WOODEN";

    public static function getType(): string
    {
        return self::ID;
    }
}