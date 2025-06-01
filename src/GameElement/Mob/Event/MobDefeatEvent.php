<?php

namespace App\GameElement\Mob\Event;

use App\Entity\Game\MapObject;

readonly class MobDefeatEvent
{
    public function __construct(
        protected mixed       $from,
        protected MapObject $defeatedMob,
    )
    {
    }

    public function getFrom(): mixed
    {
        return $this->from;
    }

    public function getDefeatedMob(): MapObject
    {
        return $this->defeatedMob;
    }
}