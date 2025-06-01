<?php

namespace App\GameElement\Map\Component\Spawn;

readonly class ObjectSpawn
{
    public function __construct(
        protected string $objectId,
        protected int    $maxAvailability,
        protected float  $spawnRate
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

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}