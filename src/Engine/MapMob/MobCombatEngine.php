<?php

namespace App\Engine\MapMob;

use App\Engine\Combat\CombatSystem;
use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatFinishEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use App\GameElement\Reward\RewardRecipeInterface;
use App\Repository\Game\MapSpawnedMobRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class MobCombatEngine implements EventSubscriberInterface
{

    public function __construct(
        protected MapSpawnedMobRepository $mapSpawnedMobRepository,
        protected RewardEngine            $rewardEngine,
        private HealthEngine $healthEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CombatOffensiveStatsCalculateEvent::class => [
                ['calculateAttack', 0],
            ],
            CombatDefensiveStatsCalculateEvent::class => [
                ['calculateDefense', 0],
            ],
            CombatDamageInflictedEvent::class => [
                ['receiveDamage', 0],
            ],
            CombatFinishEvent::class => [
                ['reward', 0],
            ],
        ];
    }

    public function calculateAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        $attacker = $event->getAttacker();
        if (!$attacker instanceof MapSpawnedMob) {
            return;
        }

        $attacker = $this->mapSpawnedMobRepository->find($attacker->getId());
        if (!$attacker instanceof MapSpawnedMob) {
            return;
        }

        $this->calculateBaseAttack($attacker, $event);
        $this->calculateBonusAttack($event);
    }

    public function calculateDefense(CombatDefensiveStatsCalculateEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof MapSpawnedMob) {
            return;
        }

        $defender = $this->mapSpawnedMobRepository->find($defender->getId());
        if (!$defender instanceof MapSpawnedMob) {
            return;
        }

        $this->calculateBaseDefense($defender, $event);
    }

    private function calculateBaseAttack(MapSpawnedMob $attacker, CombatOffensiveStatsCalculateEvent $event): void
    {
        foreach ($attacker->getOffensiveStats() as $stat) {
            $event->increase($stat::class, $stat->getValue());
        }
    }

    private function calculateBonusAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        foreach ($event->getStats()->getStats() as $stat) {
            $event->increase($stat::class, CombatSystem::getBonusAttack($stat->getValue()));

        }
    }

    private function calculateBaseDefense(MapSpawnedMob $defender, CombatDefensiveStatsCalculateEvent $event): void
    {
        foreach ($defender->getDefensiveStats() as $stat) {
            $event->increase($stat::class, $stat->getValue());
        }
    }

    public function receiveDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof MapSpawnedMob) {
            return;
        }

        $defender = $this->mapSpawnedMobRepository->find($defender->getId());
        if (!$defender instanceof MapSpawnedMob) {
            return;
        }

        $this->healthEngine->decreaseCurrentHealth($defender, $event->getDamage());
        $this->mapSpawnedMobRepository->save($defender);

        $attacker = $event->getAttacker();
        if (!$defender->getHealth()->isAlive() && $attacker instanceof RewardRecipeInterface) {
           $this->reward($defender, $attacker);
        }
    }

    private function reward(MapSpawnedMob $mob, RewardRecipeInterface $recipe): void
    {
        foreach ($mob->getMob()->getRewardOnDefeats() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $recipe));
        }
    }
}