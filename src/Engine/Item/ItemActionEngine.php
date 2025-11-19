<?php

namespace App\Engine\Item;

use App\Engine\Math;
use App\Engine\Player\PlayerItemEngine;
use App\Entity\Core\GameObject;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Healing\Component\HealingComponent;
use App\GameElement\Healing\Engine\HealingEngine;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\Repository\Data\PlayerCharacterRepository;
use RuntimeException;

readonly class ItemActionEngine
{
    public function __construct(
        private PlayerItemEngine          $playerItemEngine,
        private HealingEngine             $healingEngine,
        private NotificationEngine        $notificationEngine,
        private PlayerCharacterRepository $playerCharacterRepository,
    )
    {
    }

    public function equip(PlayerCharacter $player, GameObject $item): void
    {
        if (!$item->hasComponent(ItemEquipmentComponent::class)) {
            throw new RuntimeException('Invalid item type for equip action');
        }
        $this->playerItemEngine->equip($item, $player);
    }

    public function unequip(PlayerCharacter $player, GameObject $item): void
    {
        if (!$item->hasComponent(ItemEquipmentComponent::class)) {
            throw new RuntimeException('Invalid item type for unequip action');
        }
        $this->playerItemEngine->unequip($item, $player);
    }

    public function eat(PlayerCharacter $player, GameObject $item): void
    {
        $item = $this->playerItemEngine->takeItem($player, $item, 1);
        if ($healing = $item->getComponent(HealingComponent::class)) {
            $this->healingEngine->heal($player, $healing);
            if ($healing->getAmount() > 0.0) {
                $this->notificationEngine->success(
                    $player->getId(),
                    sprintf('+%s health restored.', Math::getStatViewValue($healing->getAmount()))
                );
            } else {
                $this->notificationEngine->danger(
                    $player->getId(),
                    sprintf('%s health lost.', Math::getStatViewValue(abs($healing->getAmount())))
                );
            }
        }
        $this->playerCharacterRepository->save($player);
    }

    public function drop(PlayerCharacter $player, GameObject $item): void
    {
        $this->playerItemEngine->takeItem($player, $item, $item->getComponent(StackComponent::class)->getCurrentQuantity());
    }
}