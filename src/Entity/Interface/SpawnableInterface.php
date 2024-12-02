<?php

namespace App\Entity\Interface;

use DateTimeImmutable;

interface SpawnableInterface
{
    public function setSpawnedAt(DateTimeImmutable $spawnedAt);
    public function getSpawnedAt(): ?DateTimeImmutable;
}