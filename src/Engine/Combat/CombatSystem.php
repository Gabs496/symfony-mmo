<?php

namespace App\Engine\Combat;

use App\Engine\Math;
use App\GameElement\Combat\Event\CombatDamageCalculateEvent;
use App\GameObject\Combat\Stat\PhysicalAttackStat;
use App\GameObject\Combat\Stat\PhysicalDefenseStat;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class CombatSystem
{
    protected const float MINIMUM_DAMAGE = 0.01;

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

        if (bccomp($event->getDamage(), 0.0, Math::SCALE) === 0) {
            $event->increaseDamage(self::MINIMUM_DAMAGE);
        }
    }
}