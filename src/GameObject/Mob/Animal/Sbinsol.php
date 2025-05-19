<?php

namespace App\GameObject\Mob\Animal;

use App\Engine\Player\Reward\MasteryReward;
use App\GameElement\Combat\Stats\PhysicalAttackStat;
use App\GameElement\Combat\Stats\PhysicalDefenseStat;
use App\GameElement\Gathering\Reward\ItemReward;
use App\GameObject\Item\Equipment\Sword\WoodenSwordPrototype;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\GameObject\Mob\AbstractBaseAnimalMob;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * This mob is wanted and created from my small brother
 */
#[AutoconfigureTag('game.object')]
readonly class Sbinsol extends AbstractBaseAnimalMob
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
                new MasteryReward(new PhysicalAttack(), 0.01),
                new ItemReward(new WoodenSwordPrototype(), 1),
            ]
        );
    }
}