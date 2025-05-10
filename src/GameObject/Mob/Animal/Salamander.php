<?php

namespace App\GameObject\Mob\Animal;

use App\GameElement\Combat\Stats\PhysicalAttackStat;
use App\GameElement\Combat\Stats\PhysicalDefenseStat;
use App\GameElement\Mastery\MasteryReward;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\GameObject\Mob\AbstractBaseAnimalMob;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
readonly class Salamander extends AbstractBaseAnimalMob
{
    public const string ID = "MOB_SALAMANDER";
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Salamander',
            maxHealth: 0.5,
            description: 'A small lizard that can spit fire.',
            combatStats: [
                new PhysicalDefenseStat(0.0),
                new PhysicalAttackStat(0.01),
            ],
            rewardOnDefeats: [
                new MasteryReward(new PhysicalAttack(), 0.01),
            ]
        );
    }
}