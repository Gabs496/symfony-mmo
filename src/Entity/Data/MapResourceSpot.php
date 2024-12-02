<?php

namespace App\Entity\Data;

use App\Entity\Game\Map;
use App\Entity\Game\MapResource;
use App\Entity\Game\Resource;
use App\Entity\Interface\SpawnableInterface;
use App\Repository\Data\MapResourceSpotRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MapResourceSpotRepository::class)]
#[Broadcast(topics: ['@="mapResourceSpots_" ~ entity.getMap().getId()'], private: true)]
class MapResourceSpot implements SpawnableInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'mapResourceSpots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Map $map = null;

    #[ORM\ManyToOne(targetEntity: MapResource::class, inversedBy: 'spots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MapResource $mapResource = null;

    #[ORM\Column]
    private int $resourceQuantity = 1;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private ?DateTimeImmutable $spawnedAt = null;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getMapResource(): ?MapResource
    {
        return $this->mapResource;
    }

    public function setMapResource(?MapResource $mapResource): static
    {
        $this->mapResource = $mapResource;

        return $this;
    }

    public function getResourceQuantity(): int
    {
        return $this->resourceQuantity;
    }

    public function setResourceQuantity(int $resourceQuantity): static
    {
        $this->resourceQuantity = $resourceQuantity;

        return $this;
    }

    public function getResource(): ?Resource
    {
        return $this->mapResource->getResource();
    }

    public function getSpawnedAt(): ?DateTimeImmutable
    {
        return $this->spawnedAt;
    }

    public function setSpawnedAt(?DateTimeImmutable $spawnedAt): static
    {
        $this->spawnedAt = $spawnedAt;

        return $this;
    }
}
