<?php

namespace App\Engine\Player;

use App\Engine\Combat\CombatSystem;
use App\Engine\Math;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Combat\Activity\AttackActivity;
use App\GameElement\Combat\CombatOpponentInterface;
use App\GameElement\Combat\CombatOpponentTokenInterface;
use App\GameElement\Combat\Engine\CombatManagerInterface;
use App\GameElement\Combat\Event\CombatDamageEvent;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\Defense;
use App\GameElement\Combat\Phase\PreCalculatedAttack;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Combat\Stats\DefensiveStat;
use App\GameElement\Combat\Stats\OffensiveStat;
use App\GameElement\Combat\Stats\PhysicalAttackStat;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class PlayerCombatManager implements CombatManagerInterface, EventSubscriberInterface
{
    public function __construct(
        protected PlayerCharacterRepository $playerCharacterRepository,
        protected NotificationEngine        $notificationEngine,
        protected HealthEngine              $healthEngine,
        protected CombatSystem              $combatSystem,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CombatDamageEvent::class => [
                ['damageInflictedEvent', 0]
            ]
        ];
    }

    public function generateAttackActivity(PlayerCharacter $player, CombatOpponentTokenInterface $opponent): AttackActivity
    {
        $playerToken = new PlayerToken($player->getId());
        //TODO: calculate attack duration
        $activity = new AttackActivity(
            $playerToken,
            $playerToken,
            $opponent,
            new PreCalculatedAttack($playerToken, $this->getAttackStatCollection($player)),
        );
        $activity->setDuration(1.0);
        return $activity;
    }

    /**
     * @param PlayerToken $token
     * @return PlayerCharacter
     */
    public function exchangeToken(CombatOpponentTokenInterface $token): CombatOpponentInterface
    {
        return $this->playerCharacterRepository->find($token->getId());
    }

    /** @param PlayerCharacter $attacker */
    public function generateAttack(CombatOpponentInterface $attacker, CombatOpponentInterface $defender): Attack
    {
        $statCollection = $this->getAttackStatCollection($attacker);
        return new Attack($attacker, $statCollection);
    }

    /** @param PlayerCharacter $defender */
    public function generateDefense(Attack $attack, CombatOpponentInterface $defender): Defense
    {
        $statCollection = new StatCollection();
        $this->calculateBaseDefense($statCollection);
        $this->calculateEquipmentDefense($defender, $statCollection);
        return new Defense($defender, $statCollection);
    }

    public function defend(Attack $attack, Defense $defense, EventDispatcherInterface $callbackDispatcher): void
    {
        $damage = $this->combatSystem->calculateDamage($attack, $defense);

        /** @var PlayerCharacter $player */
        $player = $defense->getDefender();
        $this->healthEngine->decreaseCurrentHealth($player, $damage->getValue());
        $this->playerCharacterRepository->save($player);

        $combatDamageEvent = new CombatDamageEvent($attack, $defense, $damage);
        $this->damageReceivedEvent($combatDamageEvent);
        $callbackDispatcher->dispatch($combatDamageEvent);
    }

    public function damageReceivedEvent(CombatDamageEvent $event): void
    {
        /** @var PlayerCharacter $player */
        $player = $event->getDefense()->getDefender();
        $this->notificationEngine->danger($player->getId(), 'You have received ' . Math::getStatViewValue($event->getDamage()->getValue()) . ' damage');
    }

    public function damageInflictedEvent(CombatDamageEvent $event): void
    {
        /** @var PlayerCharacter $player */
        $player = $event->getAttack()->getAttacker();
        $this->notificationEngine->success($player->getId(), 'You have inflicted ' . Math::getStatViewValue($event->getDamage()->getValue()) . ' damage');
    }

    private function getAttackStatCollection(PlayerCharacter $player): StatCollection
    {
        $statCollection = new StatCollection();
        $this->calculateBaseAttack($player, $statCollection);
        $this->calculateEquipmentAttack($player, $statCollection);
        $this->calculateBonusAttack($statCollection);
        return $statCollection;
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
}