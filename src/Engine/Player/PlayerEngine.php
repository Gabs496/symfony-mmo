<?php

namespace App\Engine\Player;

use App\Engine\Player\Event\PlayerBackpackUpdated;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class PlayerEngine
{
    public function __construct(
        private MessageBusInterface $messageBus
    )
    {
    }
    /** @throws MaxBagSizeReachedException */
    public function giveItem(PlayerCharacter $player, ItemInstance $itemInstance): void
    {
        $backPack = $player->getBackpack();
        $itemInstance->setBag($backPack);
        $backPack->addItem($itemInstance);

        $this->messageBus->dispatch(new PlayerBackpackUpdated($player->getId()));
    }

    public function takeItem(PlayerCharacter $player, AbstractItem $item, int $quantity): void
    {
        $player->getBackpack()->extract($item, $quantity);
        $this->messageBus->dispatch(new PlayerBackpackUpdated($player->getId()));

    }
}