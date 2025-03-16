<?php

namespace App\Engine\Combat;

use App\Engine\Math;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatFinishEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Mob\AbstractMobInstance;
use App\GameElement\Reward\RewardApply;
use App\GameObject\Combat\Stat\PhysicalAttackStat;
use App\GameObject\Combat\Stat\PhysicalDefenseStat;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Twig\Environment;

class PlayerCombatManager implements EventSubscriberInterface
{
    public function __construct(
        protected PlayerCharacterRepository $playerCharacterRepository,
        protected MessageBusInterface $messageBus,
        protected HubInterface $hub,
        protected Environment $twig,
        protected NotificationEngine $notificationEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CombatOffensiveStatsCalculateEvent::class => [
                ['calculateBaseAttack', 0],
                ['calculateBonusAttack', 0],
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

        $event->increase(PhysicalAttackStat::class, $attacker->getMasteryExperience(new PhysicalAttack()));
    }

    public function calculateBonusAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        $attacker = $event->getAttacker();
        if (!$attacker instanceof PlayerCharacter) {
            return;
        }

        foreach ($event->getStats()->getStats() as $stat) {
            $maximimumBonus = Math::mul($stat->getValue(), 0.1);
            $percentage = bcmul(rand(1, 100), 0.01, 2);
            $event->increase($stat::class, Math::mul($maximimumBonus, $percentage));

        }
    }

    public function calculateBaseDefense(CombatDefensiveStatsCalculateEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof PlayerCharacter) {
            return;
        }

        $event->increase(PhysicalDefenseStat::class, 0.0);
    }

    public function receiveDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefender();
        if ($defender instanceof PlayerCharacter) {
            $defender->setCurrentHealth(max(Math::sub($defender->getCurrentHealth(), $event->getDamage()), 0.0));
            $this->playerCharacterRepository->save($defender);
            $this->notificationEngine->danger($defender->getId(), 'You have received ' . $event->getDamage() . ' damage');
            $this->hub->publish(new Update('player_gui_' . $defender->getId(),
                $this->twig->load('parts/player_health.stream.html.twig')->renderBlock('update', ['player' => $defender]),
                true
            ));
        }

        $attacker = $event->getAttacker();
        if ($attacker instanceof PlayerCharacter) {
            $this->notificationEngine->success($attacker->getId(), 'You have inflicted ' . $event->getDamage() . ' damage');
        }



        $event->setIsDefenderAlive(bccomp($defender->getCurrentHealth(), 0.0, 2) > 0);
    }

    public function rewardPlayer(CombatFinishEvent $event): void
    {
        $winner = $event->getWinner();
        $loser = $event->getLoser();

        if ($winner instanceof PlayerCharacter) {
            if ($loser instanceof AbstractMobInstance) {
                $mob = $loser->getMob();
                foreach ($mob->getRewardOnDefeats() as $reward) {
                    $this->messageBus->dispatch(new RewardApply($reward, $winner));
                }
            }
        }
    }
}