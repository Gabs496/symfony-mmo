<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Activity\Event\ActivityTimeoutEvent;
use App\GameElement\Combat\Activity\AttackActivity;
use App\GameElement\Combat\Component\Combat;
use App\GameElement\Combat\Event\AttackEvent;
use App\GameElement\Combat\Event\DefendEvent;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\DefenseFinished;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\Token\TokenEngine;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CombatEngine implements EventSubscriberInterface
{
    protected array $registeredCombatManagers = [];
    public function __construct(
        protected ActivityEngine           $activityEngine,
        protected EventDispatcherInterface $eventDispatcher,
        protected TokenEngine $tokenEngine,
        #[AutowireIterator('combat.manager')]
        /** @var iterable<CombatManagerInterface> */
        protected iterable                  $combatManagers,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ActivityTimeoutEvent::class => [
                ['startAttack', 0]
            ],
        ];
    }

    public function startAttack(ActivityTimeoutEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof AttackActivity) {
            return;
        }

        $attackerToken = $activity->getAttackerToken();
        $defenderToken = $activity->getDefenderToken();

        $attacker = $this->tokenEngine->exchange($attackerToken);
        $defender = $this->tokenEngine->exchange($defenderToken);

        if (!$attacker instanceof GameObjectInterface || !$defender instanceof GameObjectInterface) {
            throw new RuntimeException(sprintf("Both %s and %s must implement %s", $attacker::class, $defender::class, GameObjectInterface::class));
        }

        $this->attack($attacker, $defender, $activity->getPreCalculatedStatCollection());
    }

    public function attack(GameObjectInterface $attacker, GameObjectInterface $defender, ?StatCollection $preCalculatedStatCollection = null): void
    {
        $attackerCombat = $attacker->getComponent(Combat::class);
        if (!$attackerCombat) {
            throw new RuntimeException(sprintf('Attacker %s does not have %s component', $attacker::class, Combat::class));
        }
        $defenderCombat = $defender->getComponent(Combat::class);
        if (!$defenderCombat) {
            throw new RuntimeException(sprintf('Defender %s does not have %s component', $defender::class, Combat::class));
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

        $attackerDispatcher = new EventDispatcher();
        if ($attackManager instanceof EventSubscriberInterface) {
            $attackerDispatcher->addSubscriber($attackManager);
        }
        $defenderDispatcher = new EventDispatcher();
        if ($defenderManager instanceof EventSubscriberInterface) {
            $defenderDispatcher->addSubscriber($defenderManager);
        }

        $attackResult = $defenderManager->defend($attack, $defense);
        $attackerDispatcher->dispatch($attackResult);
        $defenderDispatcher->dispatch(new DefenseFinished($attack, $defense, $attackResult));
    }

    public function getCombatManager(Combat $combat): CombatManagerInterface
    {
        $combatManager = $this->registeredCombatManagers[$combat->getManagerClass()] ?? null;
        if ($combatManager) {
            return $combatManager;
        }

        foreach ($this->combatManagers as $combatManager) {
            $this->registeredCombatManagers[$combatManager::class] = $combatManager;
            if ($combatManager::class === $combat->getManagerClass()) {
                return $combatManager;
            }
        }
        throw new RuntimeException('Invalid combat manager class: ' . $combat->getManagerClass());
    }
}