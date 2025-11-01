<?php

namespace App\Repository\Game;

use App\Entity\Game\MapObject;
use App\GameElement\Map\AbstractMap;
use App\Repository\RemoveEntityTrait;
use App\Repository\SaveEntityTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MapObject>
 */
class MapObjectRepository extends ServiceEntityRepository
{
    use SaveEntityTrait { save as saveEntityTrait; }
    use RemoveEntityTrait;
    public function __construct(ManagerRegistry $registry, private readonly GameObjectRepository $gameObjectRepository)
    {
        parent::__construct($registry, MapObject::class);
    }

    public function save(MapObject $entity): void
    {
        $this->gameObjectRepository->save($entity->getGameObject());
        self::saveEntityTrait($entity);
    }

    /** @return MapObject[] */
    public function findByMap(AbstractMap|string $map): array
    {
        $mapId = $map instanceof AbstractMap ? $map->getId() : $map;
        return $this->createQueryBuilder('map_object')
            ->select('map_object','game_object')
            ->innerJoin('map_object.gameObject', 'game_object')
            ->where('map_object.mapId = :map')
            ->setParameter('map', $mapId)
            ->getQuery()
            ->execute()
        ;
    }
}
