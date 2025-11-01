<?php

namespace App\GameElement\Mob\Event;

use App\GameElement\Core\GameObject\GameObjectInterface;

readonly class MobDefeatEvent
{
    public function __construct(
        protected mixed       $from,
        protected GameObjectInterface $defeatedMob,
    )
    {
    }

    public function getFrom(): mixed
    {
        return $this->from;
    }

    public function getDefeatedMob(): GameObjectInterface
    {
        return $this->defeatedMob;
    }
}