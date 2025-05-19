<?php

namespace App\GameElement\Combat\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Activity\Event\ActivityTimeoutEvent;
use App\GameElement\Combat\Activity\AttackActivity;
use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\Component\Attack;
use App\GameElement\Combat\Component\Defense;
use App\GameElement\Combat\Event\AttackEvent;
use App\GameElement\Combat\Event\CombatDamageCalculateEvent;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDamageReceivedEvent;
use App\GameElement\Combat\Event\DefendEvent;
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

        $attack = $activity->getAttack();
        $opponent = $activity->getOpponent();

        $this->attack($attack, $opponent);
    }

    public function attack(Attack $attack, CombatOpponentTokenInterface $defender): void
    {
        $this->eventDispatcher->dispatch(new AttackEvent($attack, $defender));
        $this->eventDispatcher->dispatch(new DefendEvent($attack, $defender));
    }

    public function defend(Attack $from, Defense $defense): void
    {
        $damageCalculation = new CombatDamageCalculateEvent($from, $defense);
        $this->eventDispatcher->dispatch($damageCalculation);
        $damage = $damageCalculation->getDamage();
        $this->eventDispatcher->dispatch(new CombatDamageInflictedEvent($from, $defense, $damage));
        $this->eventDispatcher->dispatch(new CombatDamageReceivedEvent($from, $defense, $damage));
    }
}