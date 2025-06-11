<?php

namespace App\Entity\Game;

use App\GameElement\Core\GameObject\AbstractGameObject;
use App\GameElement\Core\GameObject\GameObjectPrototypeInterface;
use App\GameElement\Core\GameObject\GameObjectPrototypeReference;
use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameElement\Core\Token\TokenizableInterface;
use App\GameElement\Map\Token\MapObjectToken;
use App\GameObject\Map\AbstractBaseMap;
use App\Repository\Game\MapObjectRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MapObjectRepository::class)]
#[Broadcast(topics: ['@="map_objects_" ~ entity.getMapId()'], private: true, template: 'streams/map_objects_list.stream.html.twig')]
class MapObject extends AbstractGameObject implements TokenizableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'guid')]
    protected string $id;

    #[ORM\Column(length: 50)]
    private ?string $mapId;

    #[ORM\Column(type: 'string', length: 50, nullable: false)]
    private ?string $objectId;

    #[ORM\Column(type: 'json_document', nullable: false)]
    protected array $components = [];

    #[GameObjectReference(objectIdProperty: 'mapId')]
    protected AbstractBaseMap $map;

    #[GameObjectPrototypeReference(objectPrototypeIdProperty: 'objectId')]
    protected GameObjectPrototypeInterface $prototype;

    #[ORM\Column(type: 'datetime_immutable')]
    protected DateTimeImmutable $spawnedAt;

    public function __construct(AbstractBaseMap $map, GameObjectPrototypeInterface $object, array $components = [])
    {
        parent::__construct(Uuid::v7(), $components);
        $this->map = $map;
        $this->mapId = $map->getId();
        $this->objectId = $object->getId();
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

    public function getObjectId(): ?string
    {
        return $this->objectId;
    }

    public function getPrototype(): GameObjectPrototypeInterface
    {
        return $this->prototype;
    }

    public function setPrototype(GameObjectPrototypeInterface $prototype): void
    {
        $this->prototype = $prototype;
    }

    public function cloneComponent(): void
    {
        $components = $this->getComponents();
        $this->components = [];
        foreach ($components as $component) {
            $this->setComponent($component::class, clone $component);
        }
    }

    public function getSpawnedAt(): DateTimeImmutable
    {
        return $this->spawnedAt;
    }

    public function getToken(): MapObjectToken
    {
        return new MapObjectToken($this->id);
    }
}
