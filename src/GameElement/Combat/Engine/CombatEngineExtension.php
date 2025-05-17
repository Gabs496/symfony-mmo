<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Activity\Event\ActivityTimeoutEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Combat\Activity\CombatActivity;
use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\Component\Attack;
use App\GameElement\Combat\Component\Defense;
use App\GameElement\Combat\Event\AttackEvent;
use App\GameElement\Combat\Event\CombatDamageCalculateEvent;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\DefendEvent;
use App\GameElement\Combat\StatCollection;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CombatEngineExtension implements EventSubscriberInterface
{
    public function __construct(
        protected ActivityEngine $activityEngine,
        protected EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeActivityStartEvent::class => [
                ['calculateRoundDuration', 0]
            ],
            ActivityTimeoutEvent::class => [
                ['fight', 0]
            ],
        ];
    }

    public function calculateRoundDuration(BeforeActivityStartEvent $event): void
    {
        if (!$event->getActivity() instanceof CombatActivity) {
            return;
        }

        //TODO: check if both opponents are alive

        //TODO: Implement combat round duration calculation
        $event->getActivity()->setDuration(1.0);
    }

    public function fight(ActivityTimeoutEvent $event): void
    {
        $timeout = $event->getTimeout();
        $activity = $timeout->getActivity();
        if (!$activity instanceof CombatActivity) {
            return;
        }

        $opponentA = $activity->getFirstOpponent();
        $opponentB = $activity->getSecondOpponent();

        $this->attack($opponentA, $opponentB);
        $this->attack($opponentB, $opponentA);
    }

    public function attack(CombatOpponentInterface $attacker, CombatOpponentInterface $defender, ?StatCollection $statCollection = null): void
    {
        if (!$statCollection) {
            $this->eventDispatcher->dispatch(new AttackEvent($attacker, $defender));
            return;
        }

        $attack = new Attack($attacker, $statCollection);
        $this->eventDispatcher->dispatch(new DefendEvent($attack, $defender));
    }

    public function defend(Attack $from, CombatOpponentInterface $defender, StatCollection $defenderStat): void
    {
        $defense = new Defense($defender, $defenderStat);
        $damageCalculation = new CombatDamageCalculateEvent($from, $defense);
        $this->eventDispatcher->dispatch($damageCalculation);
        $damage = $damageCalculation->getDamage();
        $this->eventDispatcher->dispatch(new CombatDamageInflictedEvent($from, $defense, $damage));
    }
}