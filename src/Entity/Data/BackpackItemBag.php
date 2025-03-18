<?php

namespace App\Entity\Data;

use Doctrine\ORM\Mapping\Entity;

#[Entity]
class BackpackItemBag extends ItemBag
{
    public const float BASE_SIZE = 10.0;

    public function __construct(PlayerCharacter $player)
    {
        parent::__construct($player, self::BASE_SIZE);
    }
}