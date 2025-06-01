<?php

namespace App\GameElement\Gathering\Spawn;

use App\GameElement\Map\Component\Spawn\ObjectSpawn;

readonly class ResourceSpawn extends ObjectSpawn
{
    public function __construct(
        string $objectId,
        int    $maxAvailability,
        float  $spawnRate,
        protected int $minSpotAvailability,
        protected  int $maxSpotAvailability,
    )
    {
        parent::__construct($objectId, $maxAvailability, $spawnRate);
    }

    public function getMinSpotAvailability(): int
    {
        return $this->minSpotAvailability;
    }

    public function getMaxSpotAvailability(): int
    {
        return $this->maxSpotAvailability;
    }
}