<?php

namespace App\GameElement\Mob\Combat;

use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Engine\CombatEngine;
use App\GameElement\Combat\Engine\CombatManagerInterface;
use App\GameElement\Combat\Engine\CombatSystemInterface;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\AttackResult;
use App\GameElement\Combat\Phase\Damage;
use App\GameElement\Combat\Phase\Defense;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Health\Component\HealthComponent;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\Mob\Event\MobDefeatEvent;
use App\Repository\Game\GameObjectRepository;
use App\Repository\Game\MapObjectRepository;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class MobCombatManager implements CombatManagerInterface
{
    public function __construct(
        private GameObjectRepository     $gameObjectRepository,
        private MapObjectRepository      $mapObjectRepository,
        private HealthEngine             $healthEngine,
        private CombatSystemInterface    $combatSystem,
        private EventDispatcherInterface $eventDispatcher,
        private CombatEngine             $combatEngine,
    )
    {
    }

    public static function getId(): string
    {
        return 'mob_combat_manager';
    }

    public function generateAttack(GameObjectInterface $attacker, GameObjectInterface $defender): Attack
    {
        if (!$combat = $attacker->getComponent(CombatComponent::class)) {
            throw new RuntimeException(sprintf('Attacker does not have %s component', CombatComponent::class));
        }
        $statCollection = new StatCollection();
        $this->calculateBaseAttack($combat, $statCollection);
        $this->calculateBonusAttack($statCollection);
        return new Attack($attacker, $statCollection);
    }

    public function generateDefense(Attack $attack, GameObjectInterface $defender): Defense
    {
        if (!$combat = $defender->getComponent(CombatComponent::class)) {
            throw new RuntimeException(sprintf('Defender does not have %s component', CombatComponent::class));
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
        if (!$health = $defender->getComponent(HealthComponent::class)) {
            throw new RuntimeException(sprintf('Defender %s:%s does not have %s component', $defender::class, $defender->getId(), HealthComponent::class));
        }
        if (!$health->isAlive()) {
            $this->mapObjectRepository->remove($this->mapObjectRepository->findOneBy(['gameObject' => $defender]));
            $this->gameObjectRepository->remove($defender);
            $attackResult->setIsDefeated(true);
        }

        return $attackResult;
    }

    public function afterAttack(AttackResult $attackResult)
    {

    }

    public function afterDefense(AttackResult $defenseResult): void
    {
        $defender = $defenseResult->getDefense()->getDefender();
        $attacker = $defenseResult->getAttack()->getAttacker();

        if ($defenseResult->isDefeated()) {
            $this->eventDispatcher->dispatch(new MobDefeatEvent($attacker, $defender));
        }

        if (!$health = $defender->getComponent(HealthComponent::class)) {
            throw new RuntimeException(sprintf('Defender %s:%s does not have %s component', $defender::class, $defender->getId(), HealthComponent::class));
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

    private function calculateBaseAttack(CombatComponent $attacker, StatCollection $statCollection): void
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

    private function calculateBaseDefense(CombatComponent $defender, StatCollection $statCollection): void
    {
        foreach ($defender->getDefensiveStats() as $stat) {
            $statCollection->increase($stat::class, $stat->getValue());
        }
    }
}