<?php

namespace App\Entity\Game;

use App\GameElement\Core\GameObject\Attribute\GameObjectReference;
use App\GameObject\Map\AbstractBaseMap;
use App\Repository\Game\MapObjectRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MapObjectRepository::class)]
#[ORM\Index(fields: ['mapId'])]
#[Broadcast(topics: ['@="map_objects_" ~ entity.getMapId()'], private: true, template: 'streams/map_objects_list.stream.html.twig')]
class MapObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(length: 50)]
    private ?string $mapId;

    #[GameObjectReference(objectIdProperty: 'mapId')]
    private AbstractBaseMap $map;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $spawnedAt;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private GameObject $gameObject;

    public function __construct(AbstractBaseMap $map, GameObject $gameObject)
    {
        $this->id = Uuid::v7();
        $this->map = $map;
        $this->mapId = $map->getId();
        $this->gameObject = $gameObject;
        $this->spawnedAt = new DateTimeImmutable();
    }

    public function getMapId(): ?string
    {
        return $this->mapId;
    }

    public function setMapId(string $mapId): static
    {
        $this->mapId = $mapId;

        return $this;
    }

    public function getSpawnedAt(): DateTimeImmutable
    {
        return $this->spawnedAt;
    }

    public function getMap(): AbstractBaseMap
    {
        return $this->map;
    }

    public function getGameObject(): ?GameObject
    {
        return $this->gameObject;
    }

    public function setGameObject(GameObject $gameObject): static
    {
        $this->gameObject = $gameObject;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return '';
    }
}
