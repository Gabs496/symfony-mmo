<?php

namespace App\GameElement\Combat\Activity;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Combat\Event\CombatDamageCalculateEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatFinishEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Exception\DamageNotCalculatedException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CombatEngineExtension implements EventSubscriberInterface
{
    public function __construct(
        protected ActivityEngine $activityEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeActivityStartEvent::class => [
                ['calculateRoundDuration', 0,]
            ],
            ActivityEndEvent::class => [
                ['fight', 0]
            ]
        ];
    }

    public function calculateRoundDuration(BeforeActivityStartEvent $event): void
    {
        if (!$event->getActivity() instanceof CombatActivity) {
            return;
        }

        //TODO: Implement combat round duration calculation
        $event->getActivity()->setDuration(1.0);
    }

    public function fight(ActivityEndEvent $event, string $eventName, EventDispatcherInterface $eventDispatcher): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof CombatActivity) {
            return;
        }

        $opponentA = $activity->getFirstOpponent();
        $opponentB = $activity->getSecondOpponent();

        $event = $this->attack($opponentA, $opponentB, $eventDispatcher);
        if (!$event->isDefenderAlive()) {
            $eventDispatcher->dispatch(new CombatFinishEvent($opponentA, $opponentB));
            return;
        }

        $event = $this->attack($opponentB, $opponentA, $eventDispatcher);
        if (!$event->isDefenderAlive()) {
            $eventDispatcher->dispatch(new CombatFinishEvent($opponentB, $opponentA));
            return;
        }

        $this->activityEngine->run($opponentA, new CombatActivity($opponentA, $opponentB));
    }

    /**
     * @throws DamageNotCalculatedException
     */
    protected function attack(object $attacker, object $defender, EventDispatcherInterface $eventDispatcher): CombatDamageInflictedEvent
    {
        $offensiveStats = new CombatOffensiveStatsCalculateEvent($attacker, $defender);
        $eventDispatcher->dispatch($offensiveStats);

        $defensiveStats = new CombatDefensiveStatsCalculateEvent($attacker, $defender);
        $eventDispatcher->dispatch($defensiveStats);
        $damageCalculation = new CombatDamageCalculateEvent(
            $offensiveStats->getStats(),
            $defensiveStats->getStats()
        );
        $eventDispatcher->dispatch($damageCalculation);

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
        $eventDispatcher->dispatch($damageEvent);

        return $damageEvent;
    }
}