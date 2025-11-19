<?php

namespace App\Entity\Map;

use App\Entity\Core\GameObject;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameObject\Map\AbstractBaseMap;
use App\Repository\Game\MapObjectRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MapObjectRepository::class)]
#[ORM\Index(fields: ['map'])]
class MapObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'guid')]
    private string $id;

    /** @var AbstractBaseMap  */
    #[ORM\Column(name: 'map_id', type: 'game_object')]
    private GameObjectInterface $map;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $spawnedAt;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private GameObject $gameObject;

    public function __construct(AbstractBaseMap $map, GameObject $gameObject)
    {
        $this->id = Uuid::v7();
        $this->map = $map;
        $this->gameObject = $gameObject;
        $this->spawnedAt = new DateTimeImmutable();
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
