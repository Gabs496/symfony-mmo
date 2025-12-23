<?php

namespace App\Engine\Player;

use App\Engine\Math;
use App\Entity\Core\GameObject;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Character\Component\CharacterComponent;
use App\GameElement\Character\Engine\HealthEngine;
use App\GameElement\Combat\Component\CombatComponent;
use App\GameElement\Combat\Component\Stat\DefensiveStat;
use App\GameElement\Combat\Component\Stat\OffensiveStat;
use App\GameElement\Combat\Engine\CombatManagerInterface;
use App\GameElement\Combat\Engine\CombatSystemInterface;
use App\GameElement\Combat\Phase\Attack;
use App\GameElement\Combat\Phase\AttackResult;
use App\GameElement\Combat\Phase\Defense;
use App\GameElement\Combat\StatCollection;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\GameElement\Render\Component\RenderComponent;
use App\Repository\Data\PlayerCharacterRepository;
use App\Repository\Game\GameObjectRepository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class PlayerCombatManager implements CombatManagerInterface
{
    public const string ID = 'player_combat_manager';

    public function __construct(
        private PlayerCharacterRepository $playerCharacterRepository,
        private NotificationEngine        $notificationEngine,
        private HealthEngine              $healthEngine,
        private CombatSystemInterface     $combatSystem,
        private GameObjectRepository $gameObjectRepository,
    )
    {
    }

    public static function getId(): string
    {
        return self::ID;
    }

    public function generateAttack(GameObjectInterface $attacker, GameObjectInterface $defender): Attack
    {
        $statCollection = $this->getAttackStats($attacker);
        return new Attack($attacker, $statCollection);
    }

    public function generateDefense(Attack $attack, GameObjectInterface $defender): Defense
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $defender]);
        $statCollection = new StatCollection();
        $this->calculateBaseDefense($statCollection);
        $this->calculateEquipmentDefense($player, $statCollection);
        return new Defense($defender, $statCollection);
    }

    public function defend(Attack $attack, Defense $defense): AttackResult
    {
        $damage = $this->combatSystem->calculateDamage($attack, $defense);

        /** @var GameObject $defender */
        $defender = $defense->getDefender();
        $this->healthEngine->decreaseCurrentHealth($defender, $damage->getValue());
        $this->gameObjectRepository->save($defender);

        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $defense->getDefender()]);
        $this->notificationEngine->danger($player->getId(), '<span class="fas fa-shield"></span> You have received ' . Math::getStatViewValue($damage->getValue()) . ' damage');

        return new AttackResult($attack, $defense, $damage, !$defender->getComponent(CharacterComponent::class)->isAlive());
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function afterAttack(AttackResult $attackResult): void
    {
        /** @var PlayerCharacter $player */
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $attackResult->getAttack()->getAttacker()]);
        $this->notificationEngine->success($player->getId(), '<span class="fas fa-sword"></span> You have inflicted ' . Math::getStatViewValue($attackResult->getDamage()->getValue()) . ' damage');

        $defender = $attackResult->getDefense()->getDefender();

        if ($attackResult->isDefeated()) {
            if ($render = $defender->getComponent(RenderComponent::class)) {
                $this->notificationEngine->success($player->getId(), '<span class="fas fa-swords"></span> You have defeated ' . $render->getName());
            }
        }
    }

    public function afterDefense(AttackResult $defenseResult)
    {
        // TODO: Implement afterDefense() method.
    }

    private function getAttackStats(GameObjectInterface $gameObject): StatCollection
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $gameObject]);
        $statCollection = new StatCollection();
        $this->calculateBaseAttack($player, $statCollection);
        $this->calculateEquipmentAttack($player, $statCollection);
        $this->calculateBonusAttack($statCollection);
        return $statCollection;
    }

    private function calculateBaseAttack(PlayerCharacter $attacker, StatCollection $statCollection): void
    {
        $combatComponent = $attacker->getGameObject()->getComponent(CombatComponent::class);
        foreach ($combatComponent->getOffensiveStats() as $stat) {
            $statCollection->increase($stat::class, $stat->getValue());
        }
    }

    private function calculateEquipmentAttack(PlayerCharacter $attacker, StatCollection $statCollection): void
    {
        foreach ($attacker->getEquipment()->getItems() as $itemObject) {
            /** @var GameObject $item */
            $item = $itemObject->getGameObject();
            $equipmentComponent = $item->getComponent(ItemEquipmentComponent::class);
            foreach ($equipmentComponent->getStats() as $stat) {
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
        foreach ($defender->getEquipment()->getItems() as $itemObject) {
            /** @var GameObject $item */
            $item = $itemObject->getGameObject();
            $equipmentComponent = $item->getComponent(ItemEquipmentComponent::class);
            foreach ($equipmentComponent->getStats() as $stat) {
                if ($stat instanceof DefensiveStat) {
                    $statCollection->increase($stat::class, $stat->getValue());
                }
            }
        }
    }
}