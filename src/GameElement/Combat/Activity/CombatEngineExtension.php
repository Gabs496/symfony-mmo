<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Activity\Event\ActivityTimeoutEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\Event\CombatDamageCalculateEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Exception\DamageNotCalculatedException;
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
                ['calculateRoundDuration', 0,]
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

    /**
     * @throws DamageNotCalculatedException
     */
    protected function attack(CombatOpponentInterface $attacker, CombatOpponentInterface $defender): CombatDamageInflictedEvent
    {
        $offensiveStats = new CombatOffensiveStatsCalculateEvent($attacker, $defender);
        $this->eventDispatcher->dispatch($offensiveStats);

        $defensiveStats = new CombatDefensiveStatsCalculateEvent($attacker, $defender);
        $this->eventDispatcher->dispatch($defensiveStats);
        $damageCalculation = new CombatDamageCalculateEvent(
            $offensiveStats->getStats(),
            $defensiveStats->getStats()
        );
        $this->eventDispatcher->dispatch($damageCalculation);

        if ($damageCalculation->getDamage() === null) {
            throw new DamageNotCalculatedException(sprintf("Damage not calculated: check if %s event has been listened", self::class));
        }

        $damageEvent = new CombatDamageInflictedEvent(
            $attacker,
            $damageCalculation->getDamage(),
            $defender,
            $offensiveStats->getStats(),
            $defensiveStats->getStats(),
        );
        $this->eventDispatcher->dispatch($damageEvent);

        return $damageEvent;
    }
}