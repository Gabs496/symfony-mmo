<?php

namespace App\Repository;

trait RemoveEntityTrait
{
    public function remove(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}