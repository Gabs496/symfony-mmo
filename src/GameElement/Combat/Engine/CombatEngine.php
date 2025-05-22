<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Activity\Event\ActivityTimeoutEvent;
use App\GameElement\Combat\Activity\AttackActivity;
use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\Event\AttackEvent;
use App\GameElement\Combat\Event\DefendEvent;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\PreCalculatedAttack;
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

        $attackerToken = $activity->getAttacker();
        $defenderToken = $activity->getOpponent();

        $attackManager = $this->getCombatManagerFromToken($attackerToken);
        $defenderManager = $this->getCombatManagerFromToken($defenderToken);

        $attacker = $attackManager->exchangeToken($attackerToken);
        $defender = $defenderManager->exchangeToken($defenderToken);

        $this->attack($attacker, $defender, $activity->getPreCalculatedAttack());
    }

    public function attack(CombatOpponentInterface $attacker, CombatOpponentInterface $defender, ?PreCalculatedAttack $preCalculatedAttack = null): void
    {
        $attackManager = $this->getCombatManager($attacker::class);
        $defenderManager = $this->getCombatManager($defender::class);

        if ($preCalculatedAttack) {
            $attack = new Attack($attacker, $preCalculatedAttack->getStatCollection());
        } else {
            $attack = $attackManager->generateAttack($attacker, $defender);
        }
        $this->eventDispatcher->dispatch(new AttackEvent($attack, $defender));

        $defense = $defenderManager->generateDefense($attack,$defender);
        $this->eventDispatcher->dispatch(new DefendEvent($attack, $defense));

        $callbackDispatcher = new EventDispatcher();
        if ($attackManager instanceof EventSubscriberInterface) {
            $callbackDispatcher->addSubscriber($attackManager);
        }

        $defenderManager->defend($attack, $defense, $callbackDispatcher);
    }

    protected function getCombatManagerFromToken(CombatOpponentTokenInterface $token): CombatManagerInterface
    {
        return $this->getCombatManager($token->getCombatOpponentClass());
    }

    /** @param class-string<CombatOpponentInterface> $combatOpponentClass */
    public function getCombatManager(string $combatOpponentClass): CombatManagerInterface
    {
        $combatManager = $this->registeredCombatManagers[$combatOpponentClass::getCombatManagerClass()] ?? null;
        if ($combatManager) {
            return $combatManager;
        }

        foreach ($this->combatManagers as $combatManager) {
            $this->registeredCombatManagers[$combatManager::class] = $combatManager;
            if ($combatManager::class === $combatOpponentClass::getCombatManagerClass()) {
                return $combatManager;
            }
        }
        throw new RuntimeException('Invalid combat manager class: ' . $combatOpponentClass::getCombatManagerClass() . ' defined in ' . $combatOpponentClass::class);
    }
}