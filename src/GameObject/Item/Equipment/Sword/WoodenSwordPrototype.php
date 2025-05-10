<?php

namespace App\GameObject\Item\Equipment\Sword;

use App\GameElement\Combat\Stats\PhysicalAttackStat;
use App\GameObject\Item\AbstractItemEquipmentPrototype;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
readonly class WoodenSwordPrototype extends AbstractItemEquipmentPrototype
{
    public function __construct()
    {
        parent::__construct(
            id: 'EQUIP_SWORD_WOODEN',
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