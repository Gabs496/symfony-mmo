<?php

namespace App\Engine\Mob;

use App\Engine\Combat\CombatSystem;
use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\Engine\CombatEngine;
use App\GameElement\Combat\Engine\CombatManagerInterface;
use App\GameElement\Combat\Event\CombatDamageEvent;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\Damage;
use App\GameElement\Combat\Phase\Defense;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\Mob\Event\MobDefeatEvent;
use App\Repository\Game\MapSpawnedMobRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MobCombatManager implements CombatManagerInterface
{

    public function __construct(
        protected MapSpawnedMobRepository  $mapSpawnedMobRepository,
        protected HealthEngine             $healthEngine,
        protected CombatSystem             $combatSystem,
        protected EventDispatcherInterface $eventDispatcher,
        protected CombatEngine             $combatEngine,
    )
    {
    }

    /** @param MobToken $token
     */
    public function exchangeToken(CombatOpponentTokenInterface $token): CombatOpponentInterface
    {
        return $this->mapSpawnedMobRepository->find($token->getId());
    }

    /** @param MapSpawnedMob $attacker */
    public function generateAttack(CombatOpponentInterface $attacker, CombatOpponentInterface $defender): Attack
    {
        $statCollection = new StatCollection();
        $this->calculateBaseAttack($attacker, $statCollection);
        $this->calculateBonusAttack($statCollection);
        return new Attack($attacker, $statCollection);
    }

    /** @param MapSpawnedMob $defender */
    public function generateDefense(Attack $attack, CombatOpponentInterface $defender): Defense
    {
        $statCollection = new StatCollection();
        $this->calculateBaseDefense($defender, $statCollection);
        return new Defense($defender, $statCollection);
    }

    public function defend(Attack $attack, Defense $defense, EventDispatcherInterface $callbackDispatcher): void
    {
        $damage = $this->combatSystem->calculateDamage($attack, $defense);
        $this->receiveDamage($defense, $damage);

        $callbackDispatcher->dispatch(new CombatDamageEvent($attack, $defense, $damage));

        /** @var MapSpawnedMob $defender */
        $defender = $defense->getDefender();
        if (!$defender->getHealth()->isAlive()) {
            $this->mapSpawnedMobRepository->remove($defender);
            $attacker = $attack->getAttacker();
            $this->eventDispatcher->dispatch(new MobDefeatEvent($attacker, $defender->getMob()));
            return;
        }

        $this->counterAttack($attack, $defense);
    }

    private function counterAttack(Attack $attack, Defense $defense): void
    {
        $this->combatEngine->attack($defense->getDefender(), $attack->getAttacker());
    }

    private function receiveDamage(Defense $defense, Damage $damage): void
    {
        /** @var MapSpawnedMob $defender */
        $defender = $defense->getDefender();
        $this->healthEngine->decreaseCurrentHealth($defender, $damage->getValue());
        $this->mapSpawnedMobRepository->save($defender);
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
}