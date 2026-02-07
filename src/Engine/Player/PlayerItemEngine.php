<?php

namespace App\Engine\Player;

use PennyPHP\Core\GameObject\Entity\GameObject;
use App\GameElement\Equipment\Event\UnequipEvent;
use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\ItemBagEngine;
use App\GameElement\Item\ItemEngineInterface;
use App\GameElement\Position\PositionEngine;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class PlayerItemEngine implements ItemEngineInterface
{
    public function __construct(
        private ItemBagEngine  $itemBagEngine,
        private PositionEngine $positionEngine,
    )
    {
    }

    public function give(GameObject $to, GameObject $item): void
    {
        self::putInBackpack($to, $item);
    }

    /** @return array<GameObject> */
    public function take(GameObject $player, string $type, int $quantity): array
    {
        return self::takeFromBackpack($player, $type, $quantity);
    }

    #[AsEventListener(UnequipEvent::class)]
    private function onUnequip(UnequipEvent $event): void
    {
        self::putInBackpack($event->getFrom(), $event->getEquipment());
    }

    private function putInBackpack(GameObject $player, GameObject $item): void
    {
        $this->positionEngine->move($item, $itemBagComponent = $player->getComponent(ItemBagComponent::class), $itemBagComponent->getId());
    }

    /** @return array<GameObject> */
    private function takeFromBackpack(GameObject $player, string $type, int $quantity): array
    {
        $itemBagComponet = $player->getComponent(ItemBagComponent::class);
        return $this->itemBagEngine->findAndExtract($itemBagComponet, $type, $quantity);
    }
}