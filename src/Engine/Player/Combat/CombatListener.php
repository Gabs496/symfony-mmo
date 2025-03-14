<?php

namespace App\Engine\Player\Combat;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatFinishEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\Mastery\MasteryReward;
use App\GameElement\Reward\RewardApply;
use App\GameObject\Combat\Stat\PhysicalAttack;
use App\GameObject\Combat\Stat\PhysicalDefense;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CombatListener implements EventSubscriberInterface
{
    public function __construct(
        protected PlayerCharacterRepository $playerCharacterRepository,
        protected MessageBusInterface $messageBus,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CombatOffensiveStatsCalculateEvent::class => [
                ['calculateBaseAttack', 0],
            ],
            CombatDefensiveStatsCalculateEvent::class => [
                ['calculateBaseDefense', 0],
            ],
            CombatDamageInflictedEvent::class => [
                ['receiveDamage', 0],
            ],
            CombatFinishEvent::class => [
                ['rewardPlayer', 0],
            ],
        ];
    }

    public function calculateBaseAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        $attacker = $event->getAttacker();
        if (!$attacker instanceof PlayerCharacter) {
            return;
        }

        $event->increase(PhysicalAttack::class, $attacker->getMasteryExperience(new \App\GameObject\Mastery\Combat\PhysicalAttack()));
    }

    public function calculateBaseDefense(CombatDefensiveStatsCalculateEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof PlayerCharacter) {
            return;
        }

        $event->increase(PhysicalDefense::class, $defender->getMasteryExperience(new \App\GameObject\Mastery\Combat\PhysicalDefense()));
    }

    public function receiveDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof PlayerCharacter) {
            return;
        }

        $defender->setCurrentHealth(max($defender->getCurrentHealth() - $event->getDamage(), 0.0));
        $this->playerCharacterRepository->save($defender);

        $event->setIsDefenderAlive(bccomp($defender->getCurrentHealth(), 0.0, 2) > 0);
    }

    public function rewardPlayer(CombatFinishEvent $event): void
    {
        $winner = $event->getWinner();
        $loser = $event->getLoser();

        if ($winner instanceof PlayerCharacter) {
            $this->messageBus->dispatch(new RewardApply(new MasteryReward(new \App\GameObject\Mastery\Combat\PhysicalAttack(), 0.1), $winner));
            $this->messageBus->dispatch(new RewardApply(new MasteryReward(new \App\GameObject\Mastery\Combat\PhysicalDefense(), 0.01), $winner));
        }
    }
}