<?php

namespace App\GameObject\ItemBag;



use App\Entity\Data\ItemBag;
use App\Entity\Data\PlayerCharacter;

class BackpackItemBag extends ItemBag
{
    public const float BASE_SIZE = 10.0;

    public function __construct(PlayerCharacter $player)
    {
        parent::__construct($player, self::BASE_SIZE);
    }
}