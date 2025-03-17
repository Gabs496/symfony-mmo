<?php

namespace App\GameElement\MapMob;

use App\GameElement\Mob\AbstractMob;

abstract class AbstractMapMobSpawnInstance
{
    public function __construct(
        protected AbstractMob $resource,
    )
    {
    }

    public function getResource(): AbstractMob
    {
        return $this->resource;
    }
}