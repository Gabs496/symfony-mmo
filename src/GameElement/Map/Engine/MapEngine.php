<?php

namespace App\GameElement\Map\Engine;

use App\Entity\Map\MapObject;
use App\GameElement\Map\AbstractMap;
use App\Repository\Game\MapObjectRepository;

readonly class MapEngine
{
    public function __construct(
        private MapObjectRepository     $mapObjectRepository,
    )
    {
    }

    /** @return MapObject[] */
    public function getMapObjects(AbstractMap $map): array
    {
        return $this->mapObjectRepository->findByMap($map);
    }
}