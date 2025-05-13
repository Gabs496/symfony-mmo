<?php

namespace App\Engine\Player;

use App\Engine\Math;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatFinishEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\Combat\Stats\DefensiveStat;
use App\GameElement\Combat\Stats\OffensiveStat;
use App\GameElement\Combat\Stats\PhysicalAttackStat;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Mob\AbstractMobInstance;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Reward\RewardApply;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\Repository\Data\PlayerCharacterRepository;
use App\Repository\Game\MapSpawnedMobRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Twig\Environment;

class PlayerCombatEngine implements EventSubscriberInterface
{
    public function __construct(
        protected PlayerCharacterRepository $playerCharacterRepository,
        protected MapSpawnedMobRepository $mapSpawnedMobRepository,
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
                ['calculateAttack', 0],
            ],
            CombatDefensiveStatsCalculateEvent::class => [
                ['calculateDefense', 0],
            ],
            CombatDamageInflictedEvent::class => [
                ['receiveDamage', 0],
                ['notifyDamage', 0],
            ],
        ];
    }

    public function calculateAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        $attacker = $event->getAttacker();
        if (!$attacker instanceof \App\Engine\PlayerCharacter) {
            return;
        }

        $attacker = $this->playerCharacterRepository->find($attacker->getId());
        if (!$attacker instanceof PlayerCharacter) {
            return;
        }

        $this->calculateBaseAttack($attacker, $event);
        $this->calculateEquipmentAttack($attacker, $event);
        $this->calculateBonusAttack($event);
    }

    public function calculateDefense(CombatDefensiveStatsCalculateEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof \App\Engine\PlayerCharacter) {
            return;
        }

        $defender = $this->playerCharacterRepository->find($defender->getId());
        if (!$defender instanceof PlayerCharacter) {
            return;
        }

        $this->calculateBaseDefense($event);
        $this->calculateEquipmentDefense($defender, $event);
    }

    private function calculateBaseAttack(PlayerCharacter $attacker, CombatOffensiveStatsCalculateEvent $event): void
    {
        $event->increase(PhysicalAttackStat::class, $attacker->getMasteryExperience(new PhysicalAttack()));
    }

    private function calculateEquipmentAttack(PlayerCharacter $attacker, CombatOffensiveStatsCalculateEvent $event): void
    {
        foreach ($attacker->getEquipment()->getItems() as $itemInstance) {
            /** @var ItemEquipmentComponent $equipmentComponent */
            $equipmentComponent = $itemInstance->getComponent(ItemEquipmentComponent::class);
            foreach ($equipmentComponent->getItemStatComponent()->getStats() as $stat) {
                if ($stat instanceof OffensiveStat) {
                    $event->increase($stat::class, $stat->getValue());
                }
            }
        }
    }

    private function calculateBonusAttack(CombatOffensiveStatsCalculateEvent $event): void
    {
        foreach ($event->getStats()->getStats() as $stat) {
            $maximimumBonus = Math::mul($stat->getValue(), 0.1);
            $percentage = bcmul(rand(1, 100), 0.01, 2);
            $event->increase($stat::class, Math::mul($maximimumBonus, $percentage));

        }
    }

    private function calculateBaseDefense(CombatDefensiveStatsCalculateEvent $event): void
    {
        //Defense can only be increased by equipment or others
    }

    private function calculateEquipmentDefense(PlayerCharacter $defender, CombatDefensiveStatsCalculateEvent $event): void
    {
        foreach ($defender->getEquipment()->getItems() as $itemInstance) {
            /** @var ItemEquipmentComponent $equipmentComponent */
            $equipmentComponent = $itemInstance->getComponent(ItemEquipmentComponent::class);
            foreach ($equipmentComponent->getItemStatComponent()->getStats() as $stat) {
                if ($stat instanceof DefensiveStat) {
                    $event->increase($stat::class, $stat->getValue());
                }
            }
        }
    }

    public function receiveDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefender();
        if (!$defender instanceof \App\Engine\PlayerCharacter) {
           return;
        }

        $defender = $this->playerCharacterRepository->find($defender->getId());
        if (!$defender instanceof PlayerCharacter) {
            return;
        }

        $defender->setCurrentHealth(max(Math::sub($defender->getCurrentHealth(), $event->getDamage()), 0.0));
        $this->playerCharacterRepository->save($defender);
        $event->setIsDefenderAlive(bccomp($defender->getCurrentHealth(), 0.0, 2) > 0);

        $this->hub->publish(new Update('player_gui_' . $defender->getId(),
            $this->twig->load('parts/player_health.stream.html.twig')->renderBlock('update', ['player' => $defender]),
            true
        ));
    }

    public function notifyDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefender();
        if ($defender instanceof \App\Engine\PlayerCharacter) {
            $this->notificationEngine->danger($defender->getId(), 'You have received ' . Math::getStatViewValue($event->getDamage()) . ' damage');
        }

        $attacker = $event->getAttacker();
        if ($attacker instanceof \App\Engine\PlayerCharacter) {
            $this->notificationEngine->success($attacker->getId(), 'You have inflicted ' . Math::getStatViewValue($event->getDamage()) . ' damage');
        }
    }
}