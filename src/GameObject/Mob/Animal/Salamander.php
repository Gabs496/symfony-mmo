<?php

namespace App\GameObject\Mob\Animal;

use App\GameElement\Character\Component\CharacterComponent;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Combat\Component\Stat\PhysicalDefenseStat;
use App\GameElement\Combat\Reward\CombatStatReward;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Mob\Combat\MobCombatManager;
use App\GameElement\Mob\MobPrototypeInterface;
use App\GameElement\Render\Component\RenderComponent;

#[RenderComponent(
    name: 'Salamander',
    description: 'A small lizard that can spit fire.',
    iconPath: '/mob/mob_salamander.png'
)]
#[CharacterComponent(maxHealth: 0.1)]
#[CombatComponent([
    new PhysicalDefenseStat(0.0),
    new PhysicalAttackStat(0.01),
], MobCombatManager::ID)]
class Salamander extends AbstractGameObjectPrototype implements MobPrototypeInterface
{
    public const string ID = "MOB_SALAMANDER";

    public function getRewardOnDefeats(): array
    {
        return [
            new CombatStatReward(PhysicalAttackStat::class, 0.01),
        ];
    }

    public static function getType(): string
    {
        return self::ID;
    }
}