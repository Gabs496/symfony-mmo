<?php

namespace App\Engine\Player;

use App\Engine\Math;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Combat\Activity\AttackActivity;
use App\GameElement\Combat\Component\Stat\DefensiveStat;
use App\GameElement\Combat\Component\Stat\OffensiveStat;
use App\GameElement\Combat\Component\Stat\PhysicalAttackStat;
use App\GameElement\Combat\Engine\CombatManagerInterface;
use App\GameElement\Combat\Engine\CombatSystemInterface;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\AttackResult;
use App\GameElement\Combat\Phase\Defense;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\Token\TokenizableInterface;
use App\GameElement\Health\Component\Health;
use App\GameElement\Health\Engine\HealthEngine;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Render\Component\Render;
use App\GameObject\Mastery\Combat\PhysicalAttack;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerCombatManager implements CombatManagerInterface, EventSubscriberInterface
{
    public function __construct(
        protected PlayerCharacterRepository $playerCharacterRepository,
        protected NotificationEngine        $notificationEngine,
        protected HealthEngine              $healthEngine,
        protected CombatSystemInterface     $combatSystem,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AttackResult::class => [
                ['handleAttackResult', 0]
            ],
        ];
    }

    public static function getId(): string
    {
        return 'player_combat_manager';
    }

    public function generateAttackActivity(PlayerCharacter $player, TokenizableInterface $opponent): AttackActivity
    {
        $playerToken = $player->getToken();
        return new AttackActivity(
            $player,
            $playerToken,
            $opponent->getToken(),
            $this->getAttackStatCollection($player),
        );
    }

    /** @param PlayerCharacter $attacker */
    public function generateAttack(GameObjectInterface $attacker, GameObjectInterface $defender): Attack
    {
        $statCollection = $this->getAttackStatCollection($attacker);
        return new Attack($attacker, $statCollection);
    }

    /** @param PlayerCharacter $defender */
    public function generateDefense(Attack $attack, GameObjectInterface $defender): Defense
    {
        $statCollection = new StatCollection();
        $this->calculateBaseDefense($statCollection);
        $this->calculateEquipmentDefense($defender, $statCollection);
        return new Defense($defender, $statCollection);
    }

    public function defend(Attack $attack, Defense $defense): AttackResult
    {
        $damage = $this->combatSystem->calculateDamage($attack, $defense);

        /** @var PlayerCharacter $player */
        $player = $defense->getDefender();
        $this->healthEngine->decreaseCurrentHealth($player, $damage->getValue());
        $this->playerCharacterRepository->save($player);

        $this->notificationEngine->danger($player->getId(), '<span class="fas fa-shield"></span> You have received ' . Math::getStatViewValue($damage->getValue()) . ' damage');

        return new AttackResult($attack, $defense, $damage, !$player->getComponent(Health::class)->isAlive());
    }

    public function handleAttackResult(AttackResult $attackResult): void
    {
        /** @var PlayerCharacter $player */
        $player = $attackResult->getAttack()->getAttacker();
        $this->notificationEngine->success($player->getId(), '<span class="fas fa-sword"></span> You have inflicted ' . Math::getStatViewValue($attackResult->getDamage()->getValue()) . ' damage');

        $defender = $attackResult->getDefense()->getDefender();

        if ($attackResult->isDefeated()) {
            if ($render = $defender->getComponent(Render::class)) {
                $this->notificationEngine->success($player->getId(), '<span class="fas fa-swords"></span> You have defeated ' . $render->getName());
            }
        }
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
            $statCollection->increase($stat::class, $this->combatSystem::getBonusAttack($stat->getValue()));

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