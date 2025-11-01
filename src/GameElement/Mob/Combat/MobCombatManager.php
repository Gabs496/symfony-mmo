<?php

namespace App\GameElement\Mob\Combat;

use App\Entity\Game\GameObject;
use App\GameElement\Combat\Component\Combat;
use App\GameElement\Combat\Engine\CombatEngine;
use App\GameElement\Combat\Engine\CombatManagerInterface;
use App\GameElement\Combat\Engine\CombatSystemInterface;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\AttackResult;
use App\GameElement\Combat\Phase\Damage;
use App\GameElement\Combat\Phase\Defense;
use App\GameElement\Combat\Phase\DefenseFinished;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\Health;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\Mob\Event\MobDefeatEvent;
use App\Repository\Game\GameObjectRepository;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class MobCombatManager implements CombatManagerInterface, EventSubscriberInterface
{

    public function __construct(
        protected GameObjectRepository     $gameObjectRepository,
        protected HealthEngine             $healthEngine,
        protected CombatSystemInterface    $combatSystem,
        protected EventDispatcherInterface $eventDispatcher,
        protected CombatEngine             $combatEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DefenseFinished::class => [
                ['onDefenseFinished', 0],
            ]
        ];
    }

    public static function getId(): string
    {
        return 'mob_combat_manager';
    }

    public function generateAttack(GameObjectInterface $attacker, GameObjectInterface $defender): Attack
    {
        if (!$combat = $attacker->getComponent(Combat::class)) {
            throw new RuntimeException(sprintf('Attacker does not have %s component', Combat::class));
        }
        $statCollection = new StatCollection();
        $this->calculateBaseAttack($combat, $statCollection);
        $this->calculateBonusAttack($statCollection);
        return new Attack($attacker, $statCollection);
    }

    public function generateDefense(Attack $attack, GameObjectInterface $defender): Defense
    {
        if (!$combat = $defender->getComponent(Combat::class)) {
            throw new RuntimeException(sprintf('Defender does not have %s component', Combat::class));
        }

        $statCollection = new StatCollection();
        $this->calculateBaseDefense($combat, $statCollection);
        return new Defense($defender, $statCollection);
    }

    public function defend(Attack $attack, Defense $defense): AttackResult
    {

        $damage = $this->combatSystem->calculateDamage($attack, $defense);
        $this->receiveDamage($defense, $damage);
        $attackResult = new AttackResult($attack, $defense, $damage);


        $defender = $defense->getDefender();
        if (!$health = $defender->getComponent(Health::class)) {
            throw new RuntimeException(sprintf('Defender %s:%s does not have %s component', $defender::class, $defender->getId(), Health::class));
        }
        if (!$health->isAlive()) {
            $this->gameObjectRepository->remove($defender);
            $attackResult->setIsDefeated(true);
        }

        return $attackResult;
    }


    public function onDefenseFinished(DefenseFinished $defenseFinished): void
    {
        $defender = $defenseFinished->getDefense()->getDefender();
        $attacker = $defenseFinished->getAttack()->getAttacker();

        if ($defenseFinished->getAttackResult()->isDefeated()) {
            $this->eventDispatcher->dispatch(new MobDefeatEvent($attacker, $defender));
        }

        if (!$health = $defender->getComponent(Health::class)) {
            throw new RuntimeException(sprintf('Defender %s:%s does not have %s component', $defender::class, $defender->getId(), Health::class));
        }

        if ($health->isAlive()) {
            $this->combatEngine->attack($defender, $attacker);
        }
    }

    private function receiveDamage(Defense $defense, Damage $damage): void
    {
        $defender = $defense->getDefender();
        $this->healthEngine->decreaseCurrentHealth($defender, $damage->getValue());
        $this->gameObjectRepository->save($defender);
    }

    private function calculateBaseAttack(Combat $attacker, StatCollection $statCollection): void
    {
        foreach ($attacker->getOffensiveStats() as $stat) {
            $statCollection->increase($stat::class, $stat->getValue());
        }
    }

    private function calculateBonusAttack(StatCollection $statCollection): void
    {
        foreach ($statCollection->getStats() as $stat) {
            $statCollection->increase($stat::class, $this->combatSystem::getBonusAttack($stat->getValue()));

        }
    }

    private function calculateBaseDefense(Combat $defender, StatCollection $statCollection): void
    {
        foreach ($defender->getDefensiveStats() as $stat) {
            $statCollection->increase($stat::class, $stat->getValue());
        }
    }
}