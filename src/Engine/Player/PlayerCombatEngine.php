<?php

namespace App\Engine\Player;

use App\Engine\Combat\CombatSystem;
use App\Engine\Math;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Combat\Activity\AttackActivity;
use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\Component\Attack;
use App\GameElement\Combat\Component\Defense;
use App\GameElement\Combat\Engine\CombatEngineExtension;
use App\GameElement\Combat\Event\CombatDamageInflictedEvent;
use App\GameElement\Combat\Event\CombatDamageReceivedEvent;
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
            DefendEvent::class => [
                ['defend', 0],
            ],
            CombatDamageReceivedEvent::class => [
                ['receiveDamage', 0],
            ],
            CombatDamageInflictedEvent::class => [
                ['notifyDamage', 0],
            ],
        ];
    }

    public function defend(DefendEvent $event): void
    {
        $player = $event->getDefender();
        if (!$player instanceof PlayerToken) {
            return;
        }
        $player = $this->playerCharacterRepository->find($player->getId());
        $this->combatEngine->defend($event->getAttack(), $this->generateDefense($player));
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

    public function receiveDamage(CombatDamageReceivedEvent $event): void
    {
        $defender = $event->getDefense()->getDefender();
        if (!$defender instanceof PlayerToken) {
           return;
        }
        $defender = $this->playerCharacterRepository->find($defender->getId());

        $this->healthEngine->decreaseCurrentHealth($defender, $event->getDamage()->getValue());
        $this->playerCharacterRepository->save($defender);

        $this->notifyDamage($event);
    }

    public function notifyDamage(CombatDamageInflictedEvent|CombatDamageReceivedEvent $event): void
    {
        $defender = $event->getDefense()->getDefender();
        if ($defender instanceof PlayerToken && $event instanceof CombatDamageReceivedEvent) {
            $this->notificationEngine->danger($defender->getId(), 'You have received ' . Math::getStatViewValue($event->getDamage()->getValue()) . ' damage');
        }

        $attacker = $event->getAttack()->getAttacker();
        if ($attacker instanceof PlayerToken && $event instanceof CombatDamageInflictedEvent) {
            $this->notificationEngine->success($attacker->getId(), 'You have inflicted ' . Math::getStatViewValue($event->getDamage()->getValue()) . ' damage');
        }
    }

    public function generateAttackActivity(PlayerCharacter $player, CombatOpponentTokenInterface $opponent): AttackActivity
    {
        $statCollection = $this->getAttackStatCollection($player);
        //TODO: calculate attack duration
        $activity = new AttackActivity(
            new PlayerToken($player->getId()),
            new Attack(new PlayerToken($player->getId()), $statCollection),
            $opponent
        );
        $activity->setDuration(1.0);
        return $activity;
    }

    protected function getAttackStatCollection(PlayerCharacter $player): StatCollection
    {
        $statCollection = new StatCollection();
        $this->calculateBaseAttack($player, $statCollection);
        $this->calculateEquipmentAttack($player, $statCollection);
        $this->calculateBonusAttack($statCollection);
        return $statCollection;
    }

    protected function generateDefense(PlayerCharacter $player): Defense
    {
        $statCollection = new StatCollection();
        $this->calculateBaseDefense($statCollection);
        $this->calculateEquipmentDefense($player, $statCollection);
        return new Defense(new PlayerToken($player->getId()), $statCollection);
    }
}