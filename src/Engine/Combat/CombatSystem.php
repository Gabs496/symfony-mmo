<?php

namespace App\Engine\Combat;

use App\Engine\Math;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Combat\Component\Stat\PhysicalDefenseStat;
use App\GameElement\Combat\Engine\CombatSystemInterface;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\Damage;
use App\GameElement\Combat\Phase\Defense;

class CombatSystem implements CombatSystemInterface
{
    protected const float MINIMUM_DAMAGE = 0.01;
    protected const float MAXIMUM_BONUS_ATTACK_PERCENTAGE = 0.1;

    public function calculateDamage(Attack $attack, Defense $defense): Damage
    {
        $offensiveStats = $attack->getStatCollection();
        $defensiveStats = $defense->getStatCollection();
        $damage = new Damage();

        foreach ($offensiveStats->getStats() as $offensiveStat) {

            if ($offensiveStat instanceof PhysicalAttackStat) {
                $defenseStat = $defensiveStats->getStat(PhysicalDefenseStat::class);
                $defenseValue = Math::mul($defenseStat->getValue(), 0.1);
                $physicalDamage = max(Math::sub($offensiveStat->getValue(), $defenseValue), 0.0);
                $damage->increaseValue($physicalDamage);
                break;
            }
        }

        if (Math::compare($damage->getValue(), 0.0) === 0) {
            $damage->increaseValue(self::MINIMUM_DAMAGE);
        }

        return $damage;
    }

    public static function getBonusAttack(float $damage): float
    {
        $maximumBonusDamage = Math::mul($damage, self::MAXIMUM_BONUS_ATTACK_PERCENTAGE);
        $randomPercentage = bcmul(rand(1, 100), 0.01, 2);
        return Math::mul($maximumBonusDamage, $randomPercentage);
    }
}