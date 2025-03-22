<?php

namespace App\GameObject\Mob\Animal;

use App\GameElement\Mastery\MasteryReward;
use App\GameObject\Combat\Stat\PhysicalAttackStat;
use App\GameObject\Combat\Stat\PhysicalDefenseStat;
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
            id: 'MOB_SALAMANDER',
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