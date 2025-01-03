<?php

namespace App\GameEngine\Player;

use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;

readonly class PlayerEngine
{
    public function giveItem(PlayerCharacter $player, ItemInstance $itemInstance): void
    {
        $backPack = $player->getBackpack();
        $itemInstance->setBag($backPack);
        //TODO: manage item bag full
        $backPack->addItem($itemInstance);
    }
}