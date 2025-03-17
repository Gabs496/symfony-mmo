<?php

namespace App\GameElement\MapMob;

readonly class MapMobSpawn
{
    public function __construct(
        private string $mobId,
        private int    $maxAvailability,
        private int    $spawnFrequency,
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

    public function getSpawnFrequency(): int
    {
        return $this->spawnFrequency;
    }
}