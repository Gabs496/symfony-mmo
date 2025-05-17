<?php

namespace App\Engine\Player;

use App\Engine\Combat\CombatSystem;
use App\Engine\Math;
use App\Engine\PlayerCharacterManager;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Combat\Engine\CombatEngineExtension;
use App\GameElement\Combat\Event\AttackEvent;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\DefendEvent;
use App\GameElement\Combat\StatCollection;
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
        protected CombatEngineExtension $combatEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AttackEvent::class => [
                ['attack', 0],
            ],
            DefendEvent::class => [
                ['defend', 0],
            ],
            CombatDamageInflictedEvent::class => [
                ['receiveDamage', 0],
                ['notifyDamage', -1],
            ],
        ];
    }

    public function attack(AttackEvent $event): void
    {
        $player = $event->getAttacker();
        if (!$player instanceof PlayerCharacter) {
            if (!$player instanceof PlayerCharacterManager){
                return;
            }
            $player = $this->playerCharacterRepository->find($player->getId());
            if (!$player) {
                return;
            }
        }

        $statCollection = new StatCollection();
        $this->calculateBaseAttack($player, $statCollection);
        $this->calculateEquipmentAttack($player, $statCollection);
        $this->calculateBonusAttack($statCollection);

        $this->combatEngine->attack($player, $event->getDefender(), $statCollection);
    }

    public function defend(DefendEvent $event): void
    {
        $player = $event->getDefender();
        if (!$player instanceof PlayerCharacter) {
            if (!$player instanceof PlayerCharacterManager){
                return;
            }
            $player = $this->playerCharacterRepository->find($player->getId());
            if (!$player) {
                return;
            }
        }

        $statCollection = new StatCollection();
        $this->calculateBaseDefense($statCollection);
        $this->calculateEquipmentDefense($player, $statCollection);
        $this->combatEngine->defend($event->getAttack(), $event->getDefender(), $statCollection);
    }

    private function calculateBaseAttack(PlayerCharacter $attacker, StatCollection $statCollection): void
    {
        $statCollection->increase(PhysicalAttackStat::class, $attacker->getMasteryExperience(new PhysicalAttack()));
    }

    private function calculateEquipmentAttack(PlayerCharacter $attacker, StatCollection $statCollection): void
    {
        foreach ($attacker->getEquipment()->getItems() as $itemInstance) {
            /** @var ItemEquipmentComponent $equipmentComponent */
            $equipmentComponent = $itemInstance->getComponent(ItemEquipmentComponent::class);
            foreach ($equipmentComponent->getItemStatComponent()->getStats() as $stat) {
                if ($stat instanceof OffensiveStat) {
                    $statCollection->increase($stat::class, $stat->getValue());
                }
            }
        }
    }

    private function calculateBonusAttack(StatCollection $statCollection): void
    {
        foreach ($statCollection->getStats() as $stat) {
            $statCollection->increase($stat::class, CombatSystem::getBonusAttack($stat->getValue()));

        }
    }

    private function calculateBaseDefense(StatCollection $statCollection): void
    {
        //Defense can only be increased by equipment or others
    }

    private function calculateEquipmentDefense(PlayerCharacter $defender, StatCollection $statCollection): void
    {
        foreach ($defender->getEquipment()->getItems() as $itemInstance) {
            /** @var ItemEquipmentComponent $equipmentComponent */
            $equipmentComponent = $itemInstance->getComponent(ItemEquipmentComponent::class);
            foreach ($equipmentComponent->getItemStatComponent()->getStats() as $stat) {
                if ($stat instanceof DefensiveStat) {
                    $statCollection->increase($stat::class, $stat->getValue());
                }
            }
        }
    }

    public function receiveDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefense()->getDefender();
        if (!$defender instanceof PlayerCharacterManager) {
           return;
        }

        $defender = $this->playerCharacterRepository->find($defender->getId());
        if (!$defender instanceof PlayerCharacter) {
            return;
        }

        $this->healthEngine->decreaseCurrentHealth($defender, $event->getDamage()->getValue());
        $this->playerCharacterRepository->save($defender);
    }

    public function notifyDamage(CombatDamageInflictedEvent $event): void
    {
        $defender = $event->getDefense()->getDefender();
        if ($defender instanceof PlayerCharacter) {
            $this->notificationEngine->danger($defender->getId(), 'You have received ' . Math::getStatViewValue($event->getDamage()->getValue()) . ' damage');
        }

        $attacker = $event->getAttack()->getAttacker();
        if ($attacker instanceof PlayerCharacter) {
            $this->notificationEngine->success($attacker->getId(), 'You have inflicted ' . Math::getStatViewValue($event->getDamage()->getValue()) . ' damage');
        }
    }
}