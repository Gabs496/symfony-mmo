<?php

namespace App\Repository\Data;

use App\Entity\Data\Activity;
use App\Repository\RemoveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ActivityRepository extends ServiceEntityRepository
{
    use RemoveEntityTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    public function save(Activity $entity): void
    {
        foreach ($entity->getSteps() as $step) {
            $entity->progressStep();
            $entity->addStep(clone $step);
        }
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}