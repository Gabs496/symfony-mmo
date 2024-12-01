<?php

namespace App\GameRule;

use App\Entity\Game\MapResource;
use App\Repository\MapResourceRepository;

readonly class GameMap
{
    public function __construct(private MapResourceRepository $mapResourceRepository)
    {
    }

    public function mapResourceFullfill(MapResource $mapResource, bool $full = false): void
    {
        if (!$mapResource->hasFreeSpace()) {
            return;
        }

        $mapResource->spawnNewSpot();
        while ($full && $mapResource->hasFreeSpace()) {
            $mapResource->spawnNewSpot();
        }

        $this->mapResourceRepository->save($mapResource);
    }
}