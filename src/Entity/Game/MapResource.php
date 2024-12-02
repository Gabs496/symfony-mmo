<?php

namespace App\Entity\Game;

use App\Entity\Data\MapResourceSpot;
use App\Repository\MapResourceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Random\RandomException;

#[ORM\Entity(repositoryClass: MapResourceRepository::class)]
#[ORM\UniqueConstraint(columns: ['resource_id', 'map_id'])]
class MapResource
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 50)]
    private ?string $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resource $resource = null;

    #[ORM\ManyToOne(inversedBy: 'spawnableResources')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Map $map = null;

    #[ORM\Column]
    private int $maxGlobalAvailability = 1;

    #[ORM\Column]
    private int $maxSpotAvailability = 1;

    #[ORM\Column]
    private int $spotSpawnFrequency = 300;

    /**
     * @var Collection<int, MapResourceSpot>
     */
    #[ORM\OneToMany(targetEntity: MapResourceSpot::class, mappedBy: 'mapResource', cascade: ['persist'])]
    private Collection $spots;

    public function __construct()
    {
        $this->spots = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function setResource(?Resource $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function getMap(): ?Map
    {
        return $this->map;
    }

    public function setMap(?Map $map): static
    {
        $this->map = $map;

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
     * @return Collection<int, MapResourceSpot>
     */
    public function getSpots(): Collection
    {
        return $this->spots;
    }

    public function addSpot(MapResourceSpot $spot): static
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
        return $this->spots->reduce(function (int $carry, MapResourceSpot $spot) {
            return $carry + $spot->getResourceQuantity();
        }, 0);
    }

    public function spawnNewSpot(int $resourceQuantity = 0): void
    {
        if (!$resourceQuantity) {
            $freeSpace = $this->getFreeSpace();
            if (!$freeSpace) {
                return;
            }

            try {
                $maxResourceQuantity = min(
                    $this->getFreeSpace(),
                    $this->getMaxSpotAvailability()
                );
                $resourceQuantity = random_int(1, $maxResourceQuantity);
            } catch (RandomException $e) {
                $resourceQuantity = 1;
            }
        }

        $instance = new MapResourceSpot();
        $instance
            ->setResourceQuantity($resourceQuantity)
            ->setMap($this->getMap())
            ->setSpawnedAt(new DateTimeImmutable())
        ;
        $this->addSpot($instance);
    }
}
