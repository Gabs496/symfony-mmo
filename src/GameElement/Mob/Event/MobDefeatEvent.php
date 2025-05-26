<?php

namespace App\GameElement\Mob\Event;

use App\GameElement\Mob\AbstractMobInstance;

readonly class MobDefeatEvent
{
    public function __construct(
        protected mixed       $from,
        protected AbstractMobInstance $defeatedMob,
    )
    {
    }

    public function getFrom(): mixed
    {
        return $this->from;
    }

    public function getDefeatedMob(): AbstractMobInstance
    {
        return $this->defeatedMob;
    }
}