<?php

namespace App\Engine\MapMob;

use App\Engine\Combat\CombatSystem;
use App\Engine\Math;
use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatFinishEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\Reward\RewardApply;
use App\Repository\Game\MapSpawnedMobRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class MobCombatEngine implements EventSubscriberInterface
{

    public function __construct(
        protected MapSpawnedMobRepository $mapSpawnedMobRepository,
        protected MessageBusInterface $messageBus,
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
                ['clearMapMobIfDefeated', -1],
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

        $defender->setCurrentHealth(max(Math::sub($defender->getCurrentHealth(), $event->getDamage()), 0.0));
        $this->mapSpawnedMobRepository->save($defender);
        $event->setIsDefenderAlive(bccomp($defender->getCurrentHealth(), 0.0, 2) > 0);
    }

    public function clearMapMobIfDefeated(CombatFinishEvent $event): void
    {
        $loser = $this->mapSpawnedMobRepository->find($event->getLoser());
        if (!$loser instanceof MapSpawnedMob) {
            return;
        }

        $this->mapSpawnedMobRepository->remove($loser);
    }

    public function reward(CombatFinishEvent $event): void
    {
        $loser = $event->getLoser();

        if (!$loser instanceof MapSpawnedMob) {
            return;
        }

        $loser = $this->mapSpawnedMobRepository->find($loser->getId());
        if (!$loser instanceof MapSpawnedMob) {
            return;
        }

        foreach ($loser->getMob()->getRewardOnDefeats() as $reward) {
            $this->messageBus->dispatch(new RewardApply($reward, $event->getWinner()));
        }
    }
}