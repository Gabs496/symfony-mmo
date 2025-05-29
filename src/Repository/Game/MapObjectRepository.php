<?php

namespace App\Repository\Game;

use App\Entity\Game\MapObject;
use App\Repository\RemoveEntityTrait;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MapObject>
 */
class MapObjectRepository extends ServiceEntityRepository
{
    use SaveEntityTrait {
        save as defaultSave;
    }
    use RemoveEntityTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapObject::class);
    }

    public function save(MapObject $entity): void
    {
        $entity->cloneComponent();
        $this->defaultSave($entity);
    }
}
