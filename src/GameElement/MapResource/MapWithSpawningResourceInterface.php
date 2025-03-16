<?php

namespace App\GameElement\MapResource;

interface MapWithSpawningResourceInterface
{
    /** @return MapResourceSpawn[] */
    public function getSpawningResources(): array;
}