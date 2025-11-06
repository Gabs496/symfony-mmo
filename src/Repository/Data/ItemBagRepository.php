<?php

namespace App\Repository\Data;

use App\Entity\Data\ItemBag;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemBag>
 */
class ItemBagRepository extends ServiceEntityRepository
{
    use SaveEntityTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemBag::class);
    }

    //    /**
    //     * @return ItemInstanceBage[] Returns an array of ItemInstanceBage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ItemInstanceBage
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
