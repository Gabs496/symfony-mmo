<?php

namespace App\GameElement\Map\Component\Spawn;

readonly class ObjectSpawn
{
    public function __construct(
        private string $objectId,
        private int    $maxAvailability,
        private float  $spawnRate,
    )
    {
    }

    public function getObjectId(): string
    {
        return $this->objectId;
    }

    public function getMaxAvailability(): int
    {
        return $this->maxAvailability;
    }

    public function getSpawnRate(): float
    {
        return $this->spawnRate;
    }
}