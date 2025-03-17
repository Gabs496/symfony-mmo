<?php

namespace App\Entity\Game;

use App\Entity\Data\Activity;
use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\MapResource\AbstractMapResourceSpawnInstance;
use App\GameObject\Map\AbstractBaseMap;
use App\Interface\ConsumableInterface;
use App\Repository\Game\MapSpawnedResourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MapSpawnedResourceRepository::class)]
#[Broadcast(topics: ['@="mapAvailableActivities_" ~ entity.getMapId()'], private: true, template: 'map/MapAvailableActivity.stream.html.twig')]
class MapSpawnedResource extends AbstractMapResourceSpawnInstance implements ConsumableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $mapId = null;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $resourceId = null;

    #[ORM\Column(type: 'float')]
    private float $quantity;

    #[ORM\ManyToOne(targetEntity: Activity::class, cascade: ['persist'])]
    private ?Activity $involvingActivity = null;

    #[GameObjectReference(AbstractBaseMap::class, objectIdProperty: 'mapId')]
    private ?AbstractBaseMap $map = null;

    #[GameObjectReference(AbstractResource::class, objectIdProperty: 'resourceId')]
    protected AbstractResource $resource;

    public function __construct(string $mapId, AbstractResource $resource, float $quantity)
    {
        $this->mapId = $mapId;
        $this->resourceId = $resource->getId();
        $this->quantity = $quantity;
        parent::__construct($resource);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function consume(float $quantity = 1.0): static
    {
        $this->quantity = bcsub($this->quantity, $quantity, 2);

        return $this;
    }

    public function getMapId(): ?string
    {
        return $this->mapId;
    }

    public function isEmpty(): bool
    {
        return bccomp($this->quantity, 0.0, 2) === 0;
    }

    public function getInvolvingActivity(): ?Activity
    {
        return $this->involvingActivity;
    }

    public function setInvolvingActivity(?Activity $involvingActivity): void
    {
        $this->involvingActivity = $involvingActivity;
    }

    public function startActivity(Activity $activity): void
    {
        $this->setInvolvingActivity($activity);
    }

    public function endActivity(Activity $activity): void
    {
        $this->setInvolvingActivity(null);
    }

    public function isInvolvedInActivity(?Activity $activity = null): bool
    {
        if (!$activity) {
            return (bool)$this->involvingActivity;
        }

        return $this->involvingActivity === $activity;
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    public function setResourceId(?string $resourceId): void
    {
        $this->resourceId = $resourceId;
    }

    public function getMap(): ?AbstractBaseMap
    {
        return $this->map;
    }

    public function setMap(?AbstractBaseMap $map): void
    {
        $this->map = $map;
    }

    public function getResource(): AbstractResource
    {
        return $this->resource;
    }

    public function setResource(AbstractResource $resource): void
    {
        $this->resource = $resource;
    }
}
