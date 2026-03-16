<?php

namespace App\GameElement\Map\Repository;

use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Map\Component\MapComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InMapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InMapComponent::class);
    }

    /** @return array<InMapComponent> */
    public function findInMap(MapComponent|string $map, string $place): array
    {
        return $this->findBy(['mapId' => is_string($map) ? $map : $map->getGameObject()->getId(), 'place' => $place]);
    }
}