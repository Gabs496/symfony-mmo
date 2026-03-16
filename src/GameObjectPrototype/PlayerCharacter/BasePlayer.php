<?php

namespace App\GameObjectPrototype\PlayerCharacter;

use App\Engine\Player\PlayerCombatManager;
use App\Entity\Data\Player;
use App\GameElement\Character\Component\CharacterComponent;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Equipment\Component\EquipmentSetComponent;
use App\GameElement\Item\Component\ItemBagComponent;
use PennyPHP\Core\AbstractGameObjectPrototype;

#[Player]
#[CharacterComponent(maxHealth: 0.25)]
#[CombatComponent([
        new PhysicalAttackStat(0.0)
    ],
    PlayerCombatManager::ID
)]
#[EquipmentSetComponent(slots: ['head', 'body', 'legs', 'feet', 'left_arm', 'right_arm', 'accessory'])]
#[ItemBagComponent(maxSize: 10.0)]
class BasePlayer extends AbstractGameObjectPrototype
{
    public const string ID = "base_player";

    public static function getType(): string
    {
        return self::ID;
    }
}