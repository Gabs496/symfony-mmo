<?php

namespace App\Repository;

trait SaveEntityTrait
{
    public function save(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}