<?php

namespace App\Engine\MapMob;

use App\Engine\Combat\CombatSystem;
use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Combat\Component\Attack;
use App\GameElement\Combat\Component\Defense;
use App\GameElement\Combat\Engine\CombatEngineExtension;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\DefendEvent;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\Mob\Event\MobDefeatEvent;
use App\GameElement\Reward\Engine\RewardEngine;
use App\Repository\Game\MapSpawnedMobRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class MobCombatEngine implements EventSubscriberInterface
{

    public function __construct(
        protected MapSpawnedMobRepository  $mapSpawnedMobRepository,
        protected RewardEngine             $rewardEngine,
        protected HealthEngine             $healthEngine,
        protected CombatEngineExtension    $combatEngine,
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DefendEvent::class => [
                ['defend', 0],
            ],
            CombatDamageInflictedEvent::class => [
                ['receiveDamage', 0],
            ],
        ];
    }

    public function defend(DefendEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof MobToken) {
            return;
        }

        $defender = $this->mapSpawnedMobRepository->find($defender->getId());
        if (!$defender) {
            return;
        }

        $statCollection = new StatCollection();
        $this->calculateBaseDefense($defender, $statCollection);
        $defense = new Defense($event->getDefender(), $statCollection);
        $this->combatEngine->defend($event->getAttack(), $defense);

        $defender = $this->mapSpawnedMobRepository->find($defender->getId());
        if (!$defender) {
            return;
        }
        $attack = $this->generateAttack($defender);
        $this->combatEngine->attack($attack, $event->getAttack()->getAttacker());
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
        if (!$defender instanceof MobToken) {
            return;
        }
        $defender = $this->mapSpawnedMobRepository->find($defender->getId());
        if (!$defender) {
            return;
        }

        $this->healthEngine->decreaseCurrentHealth($defender, $event->getDamage()->getValue());
        $this->mapSpawnedMobRepository->save($defender);

        $attacker = $event->getAttack()->getAttacker();
        if (!$defender->getHealth()->isAlive()) {
            $this->mapSpawnedMobRepository->remove($defender);
            $this->eventDispatcher->dispatch(new MobDefeatEvent($attacker, $defender->getMob()));
        }
    }

    public function generateAttack(MapSpawnedMob $mobInstance): Attack
    {
        $statCollection = new StatCollection();
        $this->calculateBaseAttack($mobInstance, $statCollection);
        $this->calculateBonusAttack($statCollection);
        return new Attack(new MobToken($mobInstance->getId()), $statCollection);
    }
}