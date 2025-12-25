<?php

namespace App\GameObject\Mob\Animal;

use App\GameElement\Character\Component\CharacterComponent;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Combat\Component\Stat\PhysicalDefenseStat;
use App\GameElement\Combat\Reward\CombatStatReward;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Drop\Component\Drop;
use App\GameElement\Item\Reward\ItemRuntimeCreatedReward;
use App\GameElement\Map\Render\MapRenderTemplateComponent;
use App\GameElement\Mob\Combat\MobCombatManager;
use App\GameElement\Mob\MobPrototypeInterface;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObject\Item\Equipment\Sword\WoodenSwordPrototype;

/**
 * This mob is wanted and created from my young brother
 */
#[RenderComponent(
    name: 'Sbinsol',
    description: 'A small lizard that can control water',
    iconPath: '/mob/mob_sbinsol.png'
)]
#[CharacterComponent(maxHealth: 0.7)]
#[CombatComponent([
    new PhysicalDefenseStat(0.02),
    new PhysicalAttackStat(0.01),
], MobCombatManager::ID)]
#[MapRenderTemplateComponent('Render:MapRenderTemplate',)]
//TODO: create a MobComponent
class Sbinsol extends AbstractGameObjectPrototype implements MobPrototypeInterface
{
    public const string ID = "MOB_SBINSOL";

    /** @inheritDoc */
    public function getRewardOnDefeats(): array
    {
        return [
            new CombatStatReward(PhysicalAttackStat::class, 0.01),
            new ItemRuntimeCreatedReward(WoodenSwordPrototype::ID, 1, [new Drop(0.1)]),
        ];
    }

    public function getId(): string
    {
        return self::ID;
    }
}