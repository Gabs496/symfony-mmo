<?php

namespace App\Entity\Game;

use App\Entity\Data\MapAvailableActivity;
use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameElement\Gathering\AbstractResource;
use App\Repository\Game\MapResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MapResourceRepository::class)]
#[ORM\UniqueConstraint(columns: ['resource_id', 'map_id'])]
class MapResource
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $id = null;

    #[ORM\Column(nullable: false)]
    private ?string $resourceId = null;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $mapId = null;

    #[ORM\Column]
    private int $maxGlobalAvailability = 1;

    #[ORM\Column]
    private int $maxSpotAvailability = 1;

    #[ORM\Column]
    private int $spotSpawnFrequency = 300;

    /**
     * @var Collection<int, MapAvailableActivity>
     */
    #[ORM\OneToMany(targetEntity: MapAvailableActivity::class, mappedBy: 'mapResource', cascade: ['persist'])]
    private Collection $spots;

    #[GameObjectReference(AbstractResource::class, objectIdProperty: 'resourceId')]
    private AbstractResource $resource;

    public function __construct()
    {
        $this->spots = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    public function setResourceId(?string $resourceId): static
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    public function getMapId(): ?string
    {
        return $this->mapId;
    }

    public function setMapId(?string $mapId): static
    {
        $this->mapId = $mapId;

        return $this;
    }

    public function getMaxGlobalAvailability(): int
    {
        return $this->maxGlobalAvailability;
    }

    public function setMaxGlobalAvailability(int $maxGlobalAvailability): static
    {
        $this->maxGlobalAvailability = $maxGlobalAvailability;
        return $this;
    }

    public function getMaxSpotAvailability(): int
    {
        return $this->maxSpotAvailability;
    }

    public function setMaxSpotAvailability(int $maxSpotAvailability): static
    {
        $this->maxSpotAvailability = $maxSpotAvailability;
        return $this;
    }

    public function getSpotSpawnFrequency(): int
    {
        return $this->spotSpawnFrequency;
    }

    public function setSpotSpawnFrequency(int $spotSpawnFrequency): static
    {
        $this->spotSpawnFrequency = $spotSpawnFrequency;
        return $this;
    }

    /**
     * @return Collection<int, MapAvailableActivity>
     */
    public function getSpots(): Collection
    {
        return $this->spots;
    }

    public function addSpot(MapAvailableActivity $spot): static
    {
        if (!$this->spots->contains($spot)) {
            $this->spots->add($spot);
            $spot->setMapResource($this);
        }
        return $this;
    }

    public function hasFreeSpace(): bool
    {

        return $this->getFreeSpace() > 0;
    }

    public function getFreeSpace(): int
    {
        return $this->maxGlobalAvailability - $this->getSpaceTaken();
    }

    public function getSpaceTaken(): int
    {
        return $this->spots->reduce(function (int $carry, MapAvailableActivity $spot) {
            return $carry + $spot->getQuantity();
        }, 0);
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
