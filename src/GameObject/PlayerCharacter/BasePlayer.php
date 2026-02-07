<?php

namespace App\GameObject\PlayerCharacter;

use App\Engine\Player\PlayerCombatManager;
use App\Entity\Data\Player;
use App\GameElement\Character\Component\CharacterComponent;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use PennyPHP\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Equipment\Component\EquipmentSetComponent;
use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Position\Component\PositionComponent;

#[Player]
#[PositionComponent]
#[CharacterComponent(maxHealth: 0.25)]
#[CombatComponent([
        new PhysicalAttackStat(0.0)
    ],
    PlayerCombatManager::ID
)]
#[EquipmentSetComponent(slots: ['head', 'body', 'legs', 'feet', 'left_arm', 'right_arm', 'accessory'])]
#[ItemBagComponent(size: 10)]
class BasePlayer extends AbstractGameObjectPrototype
{
    public const string ID = "base_player";

    public static function getType(): string
    {
        return self::ID;
    }
}