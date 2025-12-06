<?php

namespace App\GameObject\Mob\Animal;

use App\Engine\Reward\MasteryReward;
use App\Entity\Core\GameObject;
use App\GameElement\Combat\Component\AbstractStat;
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
    public function make(
        array $components = [],
        string $name = 'Sbinsol',
        string $description = 'A small lizard that can control water',
        string $iconPath = '',
        float $maxHealth = 0.7,
        /** @param AbstractStat[] $combatStats */
        array $combatStats = [
            new PhysicalDefenseStat(0.02),
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
            new ItemRuntimeCreatedReward(WoodenSwordPrototype::ID, 1, [new Drop(0.1)]),
        ];
    }

    public static function getId(): string
    {
        return self::ID;
    }
}