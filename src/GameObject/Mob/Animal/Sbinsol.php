<?php

namespace App\GameObject\Mob\Animal;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Combat\Component\Stat\PhysicalDefenseStat;
use App\GameElement\Drop\Component\Drop;
use App\GameElement\Item\Reward\ItemRuntimeCreatedReward;
use App\GameObject\Item\Equipment\Sword\WoodenSwordPrototype;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\GameObject\Mob\AbstractBaseAnimalMob;

/**
 * This mob is wanted and created from my young brother
 */
class Sbinsol extends AbstractBaseAnimalMob
{
    public const string ID = "MOB_SBINSOL";
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Sbinsol',
            maxHealth: 0.7,
            description: 'A small lizard that can control water',
            combatStats: [
                new PhysicalDefenseStat(0.02),
                new PhysicalAttackStat(0.01),
            ],
            rewardOnDefeats: [
                new MasteryReward(PhysicalAttack::getId(), 0.01),
                new ItemRuntimeCreatedReward(WoodenSwordPrototype::ID, 1, [new Drop(0.1)]),
            ]
        );
    }
}