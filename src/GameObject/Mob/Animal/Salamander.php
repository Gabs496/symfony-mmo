<?php

namespace App\GameObject\Mob\Animal;

use App\GameElement\Mastery\MasteryReward;
use App\GameObject\Combat\Stat\PhysicalAttackStat;
use App\GameObject\Combat\Stat\PhysicalDefenseStat;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
readonly class Salamander extends BaseAnimalBaseMob
{

    public function __construct()
    {
        parent::__construct(
            id: 'SALAMANDER',
            name: 'Salamander',
            maxHealth: 1.0,
            description: 'A small lizard that can spit fire.'
        );
    }

    /** @inheritDoc */
    public function getCombatStats(): array
    {
        return [
            new PhysicalDefenseStat(0.0),
            new PhysicalAttackStat(0.01),
        ];
    }

    /** @inheritDoc */
    public function getRewardOnDefeats(): array
    {
        return [
            new MasteryReward(new PhysicalAttack(), 0.01),
        ];
    }
}