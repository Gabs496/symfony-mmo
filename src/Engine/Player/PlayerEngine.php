<?php

namespace App\Engine\Player;

use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;

readonly class PlayerEngine
{

    /** @throws MaxBagSizeReachedException */
    public function giveItem(PlayerCharacter $player, ItemInstance $itemInstance): void
    {
        $backPack = $player->getBackpack();
        $itemInstance->setBag($backPack);
        //TODO: manage item bag full
        $backPack->addItem($itemInstance);
    }
}