<?php

namespace App\Engine\MapMob;

use App\Engine\Combat\CombatSystem;
use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Combat\Engine\CombatEngineExtension;
use App\GameElement\Combat\Event\AttackEvent;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\DefendEvent;
use App\GameElement\Combat\StatCollection;
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
        protected HealthEngine $healthEngine,
        protected CombatEngineExtension $combatEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AttackEvent::class => [
                ['attack', 0],
            ],
            DefendEvent::class => [
                ['defend', 0],
            ],
            CombatDamageInflictedEvent::class => [
                ['receiveDamage', 0],
            ],
        ];
    }

    public function attack(AttackEvent $event): void
    {
        $attacker = $event->getAttacker();
        if (!$attacker instanceof MapSpawnedMob) {
            return;
        }

        $attacker = $this->mapSpawnedMobRepository->find($attacker->getId());
        if (!$attacker instanceof MapSpawnedMob) {
            return;
        }

        $statCollection = new StatCollection();
        $this->calculateBaseAttack($attacker, $statCollection);
        $this->calculateBonusAttack($statCollection);
        $this->combatEngine->attack($attacker, $event->getDefender(), $statCollection);
    }

    public function defend(DefendEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof MapSpawnedMob) {
            return;
        }

        $defender = $this->mapSpawnedMobRepository->find($defender->getId());
        if (!$defender instanceof MapSpawnedMob) {
            return;
        }

        $statCollection = new StatCollection();
        $this->calculateBaseDefense($defender, $statCollection);
        $this->combatEngine->defend($event->getAttack(), $defender, $statCollection);
    }

    private function calculateBaseAttack(MapSpawnedMob $attacker, StatCollection $statCollection): void
    {
        foreach ($attacker->getOffensiveStats() as $stat) {
            $statCollection->increase($stat::class, $stat->getValue());
        }
    }

    private function calculateBonusAttack(StatCollection $statCollection): void
    {
        foreach ($statCollection->getStats() as $stat) {
            $statCollection->increase($stat::class, CombatSystem::getBonusAttack($stat->getValue()));

        }
    }

    private function calculateBaseDefense(MapSpawnedMob $defender, StatCollection $statCollection): void
    {
        foreach ($defender->getDefensiveStats() as $stat) {
            $statCollection->increase($stat::class, $stat->getValue());
        }
    }

    public function receiveDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefense()->getDefender();
        if (!$defender instanceof MapSpawnedMob) {
            return;
        }

        $defender = $this->mapSpawnedMobRepository->find($defender->getId());
        if (!$defender instanceof MapSpawnedMob) {
            return;
        }

        $this->healthEngine->decreaseCurrentHealth($defender, $event->getDamage()->getValue());
        $this->mapSpawnedMobRepository->save($defender);

        $attacker = $event->getAttack()->getAttacker();
        if (!$defender->getHealth()->isAlive()) {
            $this->mapSpawnedMobRepository->remove($defender);
            if ($attacker instanceof RewardRecipeInterface) {
                $this->reward($defender, $attacker);
            }}
    }

    private function reward(MapSpawnedMob $mob, RewardRecipeInterface $recipe): void
    {
        foreach ($mob->getMob()->getRewardOnDefeats() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $recipe));
        }
    }
}