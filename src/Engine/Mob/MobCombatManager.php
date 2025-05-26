<?php

namespace App\Engine\Mob;

use App\Engine\Combat\CombatSystem;
use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Combat\HasCombatComponentInterface;
use App\GameElement\Combat\Engine\CombatEngine;
use App\GameElement\Combat\Engine\CombatManagerInterface;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\AttackResult;
use App\GameElement\Combat\Phase\Damage;
use App\GameElement\Combat\Phase\Defense;
use App\GameElement\Combat\Phase\DefenseFinished;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\Mob\Event\MobDefeatEvent;
use App\Repository\Game\MapSpawnedMobRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class MobCombatManager implements CombatManagerInterface, EventSubscriberInterface
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

    public static function getSubscribedEvents(): array
    {
        return [
            DefenseFinished::class => [
                ['counterAttack', 0],
            ]
        ];
    }

    /** @param MapSpawnedMob $attacker */
    public function generateAttack(HasCombatComponentInterface $attacker, HasCombatComponentInterface $defender): Attack
    {
        $statCollection = new StatCollection();
        $this->calculateBaseAttack($attacker, $statCollection);
        $this->calculateBonusAttack($statCollection);
        return new Attack($attacker, $statCollection);
    }

    /** @param MapSpawnedMob $defender */
    public function generateDefense(Attack $attack, HasCombatComponentInterface $defender): Defense
    {
        $statCollection = new StatCollection();
        $this->calculateBaseDefense($defender, $statCollection);
        return new Defense($defender, $statCollection);
    }

    public function defend(Attack $attack, Defense $defense): AttackResult
    {

        $damage = $this->combatSystem->calculateDamage($attack, $defense);
        $this->receiveDamage($defense, $damage);
        $attackResult = new AttackResult($attack, $defense, $damage);


        /** @var MapSpawnedMob $defender */
        $defender = $defense->getDefender();
        if (!$defender->getHealth()->isAlive()) {
            $this->mapSpawnedMobRepository->remove($defender);
            $attackResult->setIsDefeated(true);
        }

        return $attackResult;
    }


    public function counterAttack(DefenseFinished $defenseFinished): void
    {
        /** @var MapSpawnedMob $defender */
        $defender = $defenseFinished->getDefense()->getDefender();
        $attacker = $defenseFinished->getAttack()->getAttacker();
        if ($defenseFinished->getAttackResult()->isDefeated()) {
            $this->eventDispatcher->dispatch(new MobDefeatEvent($attacker, $defender->getMob()));
        }

        if ($defender->getHealth()->isAlive()) {
            $this->combatEngine->attack($defender, $attacker);
        }
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