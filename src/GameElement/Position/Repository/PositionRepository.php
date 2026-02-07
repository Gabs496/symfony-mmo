<?php

namespace App\GameElement\Position\Repository;

use PennyPHP\Core\GameObject\Entity\GameObject;
use App\GameElement\Position\Component\PositionComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PositionComponent::class);
    }

    /** @return array<PositionComponent> */
    public function findByPosition(GameObject $gameObject): array
    {
        return $this->findBy(['position' => $gameObject]);
    }
}