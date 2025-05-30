<?php

namespace App\Entity\Game;

use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObject\GameObjectPrototypeInterface;
use App\GameElement\Core\GameObject\GameObjectPrototypeReference;
use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameElement\Core\Token\TokenInterface;
use App\GameElement\Core\Token\TokenizableInterface;
use App\GameElement\Map\Token\MapObjectToken;
use App\GameObject\Map\AbstractBaseMap;
use App\Repository\Game\MapObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MapObjectRepository::class)]
#[Broadcast(topics: ['@="map_objects_" ~ entity.getMapId()'], private: true, template: 'streams/map_objects_list.stream.html.twig')]
class MapObject implements TokenizableInterface, GameObjectInterface
{
    use GameComponentOwnerTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'guid')]
    private string $id;

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

    public function __construct(AbstractBaseMap $map, GameObjectPrototypeInterface $object, array $components = [])
    {
        $this->id = Uuid::v7();
        $this->map = $map;
        $this->mapId = $map->getId();
        $this->components = $components;
        $this->objectId = $object->getId();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getToken(): TokenInterface
    {
        return new MapObjectToken($this->id);
    }
}
