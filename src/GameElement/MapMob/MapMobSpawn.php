<?php

namespace App\GameElement\MapMob;

readonly class MapMobSpawn
{
    public function __construct(
        private string $mobId,
        private int    $maxAvailability,
        private float  $spawnRate,
    )
    {
    }

    public function getMobId(): string
    {
        return $this->mobId;
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