<?php

namespace App\Engine\Combat;

use App\Engine\Math;
use App\GameElement\Combat\Event\CombatDamageCalculateEvent;
use App\GameElement\Combat\Stats\PhysicalAttackStat;
use App\GameElement\Combat\Stats\PhysicalDefenseStat;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class CombatSystem
{
    protected const float MINIMUM_DAMAGE = 0.01;
    protected const float MAXIMUM_BONUS_ATTACK_PERCENTAGE = 0.1;

    #[AsEventListener(CombatDamageCalculateEvent::class)]
    public function calculateDamage(CombatDamageCalculateEvent $event): void
    {
        $offensiveStats = $event->getOffensiveStats();
        $defensiveStats = $event->getDefensiveStats();

        foreach ($offensiveStats->getStats() as $offensiveStat) {

            if ($offensiveStat instanceof PhysicalAttackStat) {
                $defenseStat = $defensiveStats->getStat(PhysicalDefenseStat::class);
                $defenseValue = Math::mul($defenseStat->getValue(), 0.1);
                $physicalDamage = max(Math::sub($offensiveStat->getValue(), $defenseValue), 0.0);
                $event->increaseDamage($physicalDamage);
                break;
            }
        }

        if (Math::compare($event->getDamage(), 0.0) === 0) {
            $event->increaseDamage(self::MINIMUM_DAMAGE);
        }
    }

    public static function getBonusAttack(float $damage): float
    {
        $maximumBonusDamage = Math::mul($damage, self::MAXIMUM_BONUS_ATTACK_PERCENTAGE);
        $randomPercentage = bcmul(rand(1, 100), 0.01, 2);
        return Math::mul($maximumBonusDamage, $randomPercentage);
    }
}