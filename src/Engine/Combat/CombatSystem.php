<?php

namespace App\Engine\Combat;

use App\GameElement\Combat\Event\CombatDamageCalculateEvent;
use App\GameObject\Combat\Stat\PhysicalAttack;
use App\GameObject\Combat\Stat\PhysicalDefense;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class CombatSystem
{
    #[AsEventListener(CombatDamageCalculateEvent::class)]
    public function calculateDamage(CombatDamageCalculateEvent $event): void
    {
        $offensiveStats = $event->getOffensiveStats();
        $defensiveStats = $event->getDefensiveStats();

        foreach ($offensiveStats->getStats() as $offensiveStat) {

            if ($offensiveStat instanceof PhysicalAttack) {
                $defenseStat = $defensiveStats->getStat(PhysicalDefense::class);
                $physicalDamage = max($offensiveStat->getValue() - $defenseStat->getValue(), 0.0);
                $event->increaseDamage($physicalDamage);
                break;
            }
        }
    }
}