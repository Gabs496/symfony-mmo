<?php

namespace App\GameElement\Map\Component\Spawn;

readonly class ObjectSpawn
{
    public function __construct(
        protected string $prototypeId,
        protected int    $maxAvailability,
        protected float  $spawnRate
    )
    {
    }

    public function getPrototypeId(): string
    {
        return $this->prototypeId;
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