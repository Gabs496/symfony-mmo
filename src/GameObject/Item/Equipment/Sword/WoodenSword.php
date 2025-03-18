<?php

namespace App\GameObject\Item\Equipment\Sword;

use App\GameObject\Combat\Stat\PhysicalAttackStat;
use App\GameObject\Item\Equipment\AbstractBaseEquipment;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
readonly class WoodenSword extends AbstractBaseEquipment
{
    public const string ID = 'EQUIP_SWORD_WOODEN';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Wooden Sword',
            description: 'A simple sword made of chestnut wood.',
            maxCondition: 0.5,
            weight: 0.2,
        );
    }

    public function getCombatStatModifiers(): array
    {
        return [
            new PhysicalAttackStat(0.05),
        ];
    }
}