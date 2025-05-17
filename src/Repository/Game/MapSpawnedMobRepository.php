<?php

namespace App\Repository\Game;

use App\Entity\Game\MapSpawnedMob;
use App\Repository\RemoveEntityTrait;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MapSpawnedMob>
 */
class MapSpawnedMobRepository extends ServiceEntityRepository
{
    use SaveEntityTrait {
        save as defaultSave;
    }
    use RemoveEntityTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapSpawnedMob::class);
    }

    public function save(MapSpawnedMob $entity): void
    {
        $entity->cloneComponent();
        $this->defaultSave($entity);
    }

    //    /**
    //     * @return MapSpawnedMob[] Returns an array of MapSpawnedMob objects
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

    //    public function findOneBySomeField($value): ?MapSpawnedMob
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
