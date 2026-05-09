<?php

namespace App\Engine\Player;

use App\GameElement\Equipment\Event\UnequipEvent;
use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Item\Event\ItemExtractedEvent;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Item\ItemBagEngine;
use PennyPHP\Core\Entity\GameObject;
use PennyPHP\Core\GameObjectInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class PlayerItemEngine
{
    public function __construct(
        private ItemBagEngine $itemBagEngine,
    )
    {
    }

    public function give(GameObject $to, GameObject $item, int $quantity = 1): void
    {
        self::putInBackpack($to, $item, $quantity);
    }

    #[AsEventListener(UnequipEvent::class)]
    private function onUnequip(UnequipEvent $event): void
    {
        self::putInBackpack($event->getFrom(), $event->getEquipment());
    }

    private function putInBackpack(GameObject $player, GameObject $item, int $quantity = 1): void
    {
        $itemBagComponent = $player->getComponent(ItemBagComponent::class);
        $this->itemBagEngine->put($itemBagComponent, $item->getComponent(ItemComponent::class), $quantity);
    }

    /**
     * @return array<ItemExtractedEvent>
     * @throws ItemQuantityNotAvailableException
     */
    public function takeFromBackpack(GameObjectInterface $player, string $prototype, int $quantity = 1): array
    {
        $itemBagComponet = $player->getComponent(ItemBagComponent::class);
        return $this->itemBagEngine->findAndExtract($itemBagComponet, $prototype, $quantity);
    }
}