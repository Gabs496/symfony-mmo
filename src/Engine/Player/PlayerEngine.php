<?php

namespace App\Engine\Player;

use App\Engine\Player\Event\PlayerBackpackUpdateEvent;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use Psr\EventDispatcher\EventDispatcherInterface;

readonly class PlayerEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }
    /** @throws MaxBagSizeReachedException */
    public function giveItem(PlayerCharacter $player, ItemInstance $itemInstance): void
    {
        $backPack = $player->getBackpack();
        $itemInstance->setBag($backPack);
        $backPack->addItem($itemInstance);

        $this->eventDispatcher->dispatch(new PlayerBackpackUpdateEvent($player->getId()));
    }

    public function takeItem(PlayerCharacter $player, AbstractItem $item, int $quantity): void
    {
        $player->getBackpack()->extract($item, $quantity);
        $this->eventDispatcher->dispatch(new PlayerBackpackUpdateEvent($player->getId()));

    }
}