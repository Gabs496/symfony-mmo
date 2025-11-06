<?php

namespace App\Repository\Data;

use App\Entity\Data\ItemObject;
use App\Repository\RemoveEntityTrait;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemObject>
 */
class ItemObjectRepository extends ServiceEntityRepository
{
    use SaveEntityTrait;
    use RemoveEntityTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemObject::class);
    }
}
