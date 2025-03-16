<?php

namespace App\Repository\Game;

use App\Entity\Game\MapSpawnedResource;
use App\Repository\RemoveEntityTrait;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MapSpawnedResource>
 */
class MapSpawnedResourceRepository extends ServiceEntityRepository
{
    use SaveEntityTrait;
    use RemoveEntityTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapSpawnedResource::class);
    }
}
