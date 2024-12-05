<?php

namespace App\Repository\Game;

use App\Entity\Game\MapResource;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MapResource>
 */
class MapResourceRepository extends ServiceEntityRepository
{
    use SaveEntityTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapResource::class);
    }

//    /**
//     * @return MapSpawnableResourceSpot[] Returns an array of MapSpawnableResourceSpot objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MapSpawnableResourceSpot
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
