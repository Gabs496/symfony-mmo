<?php

namespace App\Engine\Player;

use App\Engine\Combat\CombatSystem;
use App\Engine\Math;
use App\Engine\PlayerCharacterManager;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDefensiveStatsCalculateEvent;
use App\GameElement\Combat\Event\CombatOffensiveStatsCalculateEvent;
use App\GameElement\Combat\Stats\DefensiveStat;
use App\GameElement\Combat\Stats\OffensiveStat;
use App\GameElement\Combat\Stats\PhysicalAttackStat;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\Repository\Data\PlayerCharacterRepository;
use App\Repository\Game\MapSpawnedMobRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Twig\Environment;

readonly class PlayerCombatEngine implements EventSubscriberInterface
{
    public function __construct(
        protected PlayerCharacterRepository $playerCharacterRepository,
        protected MapSpawnedMobRepository $mapSpawnedMobRepository,
        protected MessageBusInterface $messageBus,
        protected HubInterface $hub,
        protected Environment $twig,
        protected NotificationEngine $notificationEngine,
        protected HealthEngine $healthEngine,
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
        if (!$attacker instanceof PlayerCharacterManager) {
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
        if (!$defender instanceof PlayerCharacterManager) {
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
            $event->increase($stat::class, CombatSystem::getBonusAttack($stat->getValue()));

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
        if (!$defender instanceof PlayerCharacterManager) {
           return;
        }

        $defender = $this->playerCharacterRepository->find($defender->getId());
        if (!$defender instanceof PlayerCharacter) {
            return;
        }

        $this->healthEngine->decreaseCurrentHealth($defender, $event->getDamage());
        $this->playerCharacterRepository->save($defender);
    }

    public function notifyDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefender();
        if ($defender instanceof PlayerCharacterManager) {
            $this->notificationEngine->danger($defender->getId(), 'You have received ' . Math::getStatViewValue($event->getDamage()) . ' damage');
        }

        $attacker = $event->getAttacker();
        if ($attacker instanceof PlayerCharacterManager) {
            $this->notificationEngine->success($attacker->getId(), 'You have inflicted ' . Math::getStatViewValue($event->getDamage()) . ' damage');
        }
    }
}