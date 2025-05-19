<?php

namespace App\GameElement\Mob\Event;

use App\GameElement\Mob\AbstractMob;

readonly class MobDefeatEvent
{
    public function __construct(
        protected mixed       $from,
        protected AbstractMob $defeatedMob,
    )
    {
    }

    public function getFrom(): mixed
    {
        return $this->from;
    }

    public function getDefeatedMob(): AbstractMob
    {
        return $this->defeatedMob;
    }
}