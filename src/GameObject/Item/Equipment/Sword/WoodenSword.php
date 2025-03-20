<?php

namespace App\GameObject\Item\Equipment\Sword;

use App\GameElement\ItemEquiment\AbstractItemEquipment;
use App\GameObject\Combat\Stat\PhysicalAttackStat;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
readonly class WoodenSword extends AbstractItemEquipment
{
    public const string ID = 'EQUIP_SWORD_WOODEN';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Wooden Sword',
            description: 'A simple sword made of chestnut wood.',
            weight: 0.2,
            combatStatModifiers: [
                PhysicalAttackStat::class => new PhysicalAttackStat(0.05),
            ],
            maxCondition: 0.5
        );
    }
}