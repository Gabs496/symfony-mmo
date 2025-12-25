<?php

namespace App\GameObject\PlayerCharacter;

use App\Engine\Player\PlayerCombatManager;
use App\GameElement\Character\Component\CharacterComponent;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;

#[CharacterComponent(maxHealth: 0.25)]
#[CombatComponent([
    new PhysicalAttackStat(0.0)
], PlayerCombatManager::ID)]
class BasePlayer extends AbstractGameObjectPrototype
{
    public const string ID = "base_player";
    public function getId(): string
    {
        return self::ID;
    }
}