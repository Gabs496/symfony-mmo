<?php

namespace App\GameElement\Gathering\Spawn;

use App\GameElement\Map\Component\Spawn\ObjectSpawn;

readonly class ResourceSpawn extends ObjectSpawn
{
    public function __construct(
        string         $prototypeId,
        int            $maxAvailability,
        float          $spawnRate,
        protected int  $minSpotAvailability,
        protected  int $maxSpotAvailability,
    )
    {
        parent::__construct($prototypeId, $maxAvailability, $spawnRate);
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