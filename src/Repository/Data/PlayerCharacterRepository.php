<?php

namespace App\Repository\Data;

use App\Entity\Data\PlayerCharacter;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerCharacter>
 */
class PlayerCharacterRepository extends ServiceEntityRepository
{
    use SaveEntityTrait {
        save as defaultSave;
    }
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerCharacter::class);
    }

    public function save(PlayerCharacter $entity): void
    {
        $entity->cloneMasterySet();
        $entity->setHealth(clone $entity->getHealth());
        $this->defaultSave($entity);
    }

    //    /**
    //     * @return Character[] Returns an array of Character objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Character
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
