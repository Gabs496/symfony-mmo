<?php

namespace App\GameElement\MapMob;

interface MapWithSpawningMobInterface
{
    /** @return MapMobSpawn[] */
    public function getSpawningMobs(): array;
}