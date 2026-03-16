<?php

namespace App\GameElement\Equipment\Repository;

use App\GameElement\Equipment\Component\EquippedComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PennyPHP\Core\GameObjectInterface;

class EquippedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquippedComponent::class);
    }


    public function findEquipped(GameObjectInterface $equipmentSet, ?string $slot = null): ?EquippedComponent
    {
        return $this->findOneBy([
            'gameObject' => $equipmentSet->getId(),
            'slot' => $slot
        ]);
    }
}