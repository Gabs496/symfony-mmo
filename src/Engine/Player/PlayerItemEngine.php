<?php

namespace App\Engine\Player;

use App\GameElement\Equipment\Event\UnequipEvent;
use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Item\Event\ItemExtractedEvent;
use App\GameElement\Item\ItemBagEngine;
use App\GameElement\Item\ItemEngineInterface;
use PennyPHP\Core\Entity\GameObject;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class PlayerItemEngine implements ItemEngineInterface
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

    /** @return array<ItemExtractedEvent> */
    public function take(GameObject $player, string $type, int $quantity): array
    {
        return self::takeFromBackpack($player, $type, $quantity);
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

    /** @return array<ItemExtractedEvent> */
    private function takeFromBackpack(GameObject $player, string $type, int $quantity): array
    {
        $itemBagComponet = $player->getComponent(ItemBagComponent::class);
        return $this->itemBagEngine->findAndExtract($itemBagComponet, $type, $quantity);
    }
}