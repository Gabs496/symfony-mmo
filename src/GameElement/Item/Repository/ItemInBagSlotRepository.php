<?php

namespace App\GameElement\Item\Repository;

use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\Component\ItemInBagSlotComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ItemInBagSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemInBagSlotComponent::class);
    }


    /** @return array<ItemInBagSlotComponent> */
    public function findInBag(ItemBagComponent|string $bag): array
    {
        return $this->findBy([
            'bagId' => $bag->getGameObject()->getId() ?? $bag
        ]);
    }
}