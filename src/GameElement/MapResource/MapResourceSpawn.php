<?php

namespace App\GameElement\MapResource;

readonly class MapResourceSpawn
{
    public function __construct(
        private string $resourceId,
        private int    $maxGlobalAvailability,
        private int    $maxSpotAvailability,
        private int    $spotSpawnFrequency
    )
    {
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getMaxGlobalAvailability(): int
    {
        return $this->maxGlobalAvailability;
    }

    public function getMaxSpotAvailability(): int
    {
        return $this->maxSpotAvailability;
    }

    public function getSpotSpawnFrequency(): int
    {
        return $this->spotSpawnFrequency;
    }
}