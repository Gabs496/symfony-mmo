<?php

namespace App\GameObject\Mob\Animal;

use App\Engine\Reward\MasteryReward;
use App\Entity\Core\GameObject;
use App\GameElement\Combat\Component\AbstractStat;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Combat\Component\Stat\PhysicalDefenseStat;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\GameObject\Mob\AbstractBaseAnimalMob;

class Salamander extends AbstractBaseAnimalMob
{
    public const string ID = "MOB_SALAMANDER";

    public function make(
        array $components = [],
        string $name = 'Salamander',
        string $description = 'A small lizard that can spit fire.',
        string $iconPath = '',
        float $maxHealth = 0.1,
        /** @param AbstractStat[] $combatStats */
        array $combatStats = [
            new PhysicalDefenseStat(0.0),
            new PhysicalAttackStat(0.01),
        ],
    ): GameObject
    {
        return parent::make(
            components: $components,
            name: $name,
            description: $description,
            iconPath: $iconPath,
            maxHealth: $maxHealth,
            combatStats: $combatStats,
        );
    }

    /** @inheritDoc */
    public function getRewardOnDefeats(): array
    {
        return [
            new MasteryReward(PhysicalAttack::ID, 0.01),
        ];
    }

    public static function getId(): string
    {
        return self::ID;
    }
}