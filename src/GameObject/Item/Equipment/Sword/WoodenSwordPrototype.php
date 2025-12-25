<?php

namespace App\GameObject\Item\Equipment\Sword;

use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Item\Render\ItemBagRenderTemplateComponent;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Map\Render\MapRenderTemplateComponent;
use App\GameElement\Render\Component\RenderComponent;

#[RenderComponent(
    name: 'Wooden Sword',
    description: 'A simple sword made of chestnut wood.',
    iconPath: '/items/equip_sword_wooden.png'
)]
#[ItemComponent(weight: 0.2)]
#[ItemEquipmentComponent([
    PhysicalAttackStat::class => new PhysicalAttackStat(0.05)
])]
#[MapRenderTemplateComponent('Render:MapRenderTemplate',)]
#[ItemBagRenderTemplateComponent('Render:ItemBagRenderTemplate')]
class WoodenSwordPrototype extends AbstractGameObjectPrototype
{
    public const string ID = "EQUIP_SWORD_WOODEN";

    public function getId(): string
    {
        return self::ID;
    }
}