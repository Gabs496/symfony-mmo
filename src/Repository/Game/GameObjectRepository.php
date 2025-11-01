<?php

namespace App\Repository\Game;

use App\Entity\Game\GameObject;
use App\Repository\RemoveEntityTrait;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameObject>
 */
class GameObjectRepository extends ServiceEntityRepository
{
    use SaveEntityTrait {
        save as defaultSave;
    }
    use RemoveEntityTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameObject::class);
    }

    public function save(GameObject $entity): void
    {
        $entity->cloneComponent();
        $this->defaultSave($entity);
    }
}
