<?php

namespace App\Repository\Game;

use PennyPHP\Core\GameObject\Entity\GameObject;
use App\Repository\RemoveEntityTrait;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameObject>
 */
class GameObjectRepository extends ServiceEntityRepository
{
    use SaveEntityTrait;
    use RemoveEntityTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameObject::class);
    }
}
