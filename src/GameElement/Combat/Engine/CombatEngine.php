<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Combat\Activity\AttackActivity;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Event\AttackEvent;
use App\GameElement\Combat\Event\DefendEvent;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\DefenseFinished;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class CombatEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private ActivityEngine $activityEngine,
        #[AutowireIterator('combat.manager')]
        /** @var iterable<CombatManagerInterface> */
        private iterable $combatManagers,
    )
    {

    }

    public function startAttack(GameObjectInterface $attacker, GameObjectInterface $defender, ?StatCollection $preCalculatedStatCollection = null): void
    {
        $this->activityEngine->run(new AttackActivity($attacker, $defender, $preCalculatedStatCollection));
    }

    public function attack(GameObjectInterface $attacker, GameObjectInterface $defender, ?StatCollection $preCalculatedStatCollection = null): void
    {
        $attackerCombat = $attacker->getComponent(CombatComponent::getId());
        if (!$attackerCombat) {
            throw new RuntimeException(sprintf('Attacker %s does not have %s component', $attacker::class, CombatComponent::getId()));
        }
        $defenderCombat = $defender->getComponent(CombatComponent::getId());
        if (!$defenderCombat) {
            throw new RuntimeException(sprintf('Defender %s does not have %s component', $defender::class, CombatComponent::getId()));
        }

        $attackManager = $this->getCombatManager($attackerCombat);
        $defenderManager = $this->getCombatManager($defenderCombat);

        if ($preCalculatedStatCollection) {
            $attack = new Attack($attacker, $preCalculatedStatCollection);
        } else {
            $attack = $attackManager->generateAttack($attacker, $defender);
        }
        $this->eventDispatcher->dispatch(new AttackEvent($attack, $defender));

        $defense = $defenderManager->generateDefense($attack,$defender);
        $this->eventDispatcher->dispatch(new DefendEvent($attack, $defense));

        $attackerDispatcher = new CombatEventDispatcher();
        if ($attackManager instanceof EventSubscriberInterface) {
            $attackerDispatcher->addSubscriber($attackManager);
        }
        $defenderDispatcher = new CombatEventDispatcher();
        if ($defenderManager instanceof EventSubscriberInterface) {
            $defenderDispatcher->addSubscriber($defenderManager);
        }

        $attackResult = $defenderManager->defend($attack, $defense);
        $attackerDispatcher->dispatch($attackResult);
        $defenderDispatcher->dispatch(new DefenseFinished($attack, $defense, $attackResult));
    }

    public function getCombatManager(CombatComponent $combat): CombatManagerInterface
    {
        foreach ($this->combatManagers as $combatManager) {
                if ($combatManager::getId() === $combat->getManagerId()) {
                    return $combatManager;
                }
            }
            throw new RuntimeException('Invalid combat manager class: ' . $combat->getManagerId());
    }
}