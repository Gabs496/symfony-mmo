<?php

namespace App\GameObject\Item\Sword;

use App\GameElement\Item\AbstractItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.item')]
readonly class WoodenSword extends AbstractItem
{
    public const string ID = 'EQUIP_SWORD_WOODEN';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Wooden Sword',
            description: 'A simple sword made of chestnut wood.',
            equippable: true,
            maxCondition: 1.0,
        );
    }

}