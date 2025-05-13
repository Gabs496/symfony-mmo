<?php

namespace App\Engine\MapMob;

use App\Engine\Math;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatFinishEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\Mob\AbstractMobInstance;
use App\Repository\Game\MapSpawnedMobRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class MobCombatEngine implements EventSubscriberInterface
{

    public function __construct(
        protected MapSpawnedMobRepository $mapSpawnedMobRepository
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CombatOffensiveStatsCalculateEvent::class => [
                ['calculateBaseAttack', 0],
                ['calculateBonusAttack', 0],
            ],
            CombatDefensiveStatsCalculateEvent::class => [
                ['calculateBaseDefense', 0],
            ],
            CombatDamageInflictedEvent::class => [
                ['receiveDamage', 0],
            ],
            CombatFinishEvent::class => [
                ['clearMapMobIfDefeated', 0],
            ],
        ];
    }

    public function calculateBaseAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        $attacker = $event->getAttacker();
        if (!$attacker instanceof AbstractMobInstance) {
            return;
        }

        foreach ($attacker->getOffensiveStats() as $stat) {
            $event->increase($stat::class, $stat->getValue());
        }
    }

    public function calculateBonusAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        $attacker = $event->getAttacker();
        if (!$attacker instanceof AbstractMobInstance) {
            return;
        }

        foreach ($event->getStats()->getStats() as $stat) {
            $maximimumBonus = Math::mul($stat->getValue(), 0.1);
            $percentage = bcmul(rand(0, 100), 0.01, 2);
            $event->increase($stat::class, Math::mul($maximimumBonus, $percentage));

        }
    }

    public function calculateBaseDefense(CombatDefensiveStatsCalculateEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof AbstractMobInstance) {
            return;
        }

        foreach ($defender->getDefensiveStats() as $stat) {
            $event->increase($stat::class, $stat->getValue());
        }
    }

    public function receiveDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof AbstractMobInstance) {
            return;
        }

        $defender->setCurrentHealth(max(Math::sub($defender->getCurrentHealth(), $event->getDamage()), 0.0));
        $this->mapSpawnedMobRepository->save($defender);

        $event->setIsDefenderAlive(bccomp($defender->getCurrentHealth(), 0.0, 2) > 0);
    }

    public function clearMapMobIfDefeated(CombatFinishEvent $event): void
    {
        $loser = $event->getLoser();
        if (!$loser instanceof AbstractMobInstance) {
            return;
        }

        $this->mapSpawnedMobRepository->remove($loser);
    }
}