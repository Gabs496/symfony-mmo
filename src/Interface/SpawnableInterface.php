<?php

namespace App\Interface;

use App\Entity\Game\Map;
use DateTimeImmutable;

interface SpawnableInterface
{
    public function getSpawnedAt(): ?DateTimeImmutable;

    public function getMap(): Map;
}