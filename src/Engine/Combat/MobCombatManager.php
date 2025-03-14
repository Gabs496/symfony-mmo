<?php

namespace App\Engine\Combat;

use App\Engine\Math;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\NPC\BaseMobInstance;
use App\Repository\Game\MapSpawnedMobRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class MobCombatManager implements EventSubscriberInterface
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
        ];
    }

    public function calculateBaseAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        $attacker = $event->getAttacker();
        if (!$attacker instanceof BaseMobInstance) {
            return;
        }

        foreach ($attacker->getOffensiveStats() as $stat) {
            $event->increase($stat::class, $stat->getValue());
        }
    }

    public function calculateBonusAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        $attacker = $event->getAttacker();
        if (!$attacker instanceof BaseMobInstance) {
            return;
        }

        foreach ($event->getStats()->getStats() as $stat) {
            $maximimumBonus = Math::mul($stat->getValue(), 0.1);
            $percentage = bcmul(rand(1, 100), 0.01, 2);
            $event->increase($stat::class, Math::mul($maximimumBonus, $percentage));

        }
    }

    public function calculateBaseDefense(CombatDefensiveStatsCalculateEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof BaseMobInstance) {
            return;
        }

        foreach ($defender->getDefensiveStats() as $stat) {
            $event->increase($stat::class, $stat->getValue());
        }
    }

    public function receiveDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof BaseMobInstance) {
            return;
        }

        $defender->setCurrentHealth(max(Math::sub($defender->getCurrentHealth(), $event->getDamage()), 0.0));
        $this->mapSpawnedMobRepository->save($defender);

        $event->setIsDefenderAlive(bccomp($defender->getCurrentHealth(), 0.0, 2) > 0);
    }
}