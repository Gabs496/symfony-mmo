<?php

namespace App\GameElement\Map\Engine;

use App\GameElement\Map\AbstractMap;
use App\Repository\Game\MapObjectRepository;

readonly class MapEngine
{
    public function __construct(
        private MapObjectRepository     $mapObjectRepository,
    )
    {
    }

    public function getMapObjects(AbstractMap $map)
    {
        return $this->mapObjectRepository->findBy(['mapId' => $map->getId()]);
    }
}