<?php

namespace App\Entity\Data;

use App\Entity\ActivityType;
use App\Entity\Game\Map;
use App\Entity\Game\MapResource;
use App\Interface\ConsumableInterface;
use App\Repository\Data\MapAvailableActivityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MapAvailableActivityRepository::class)]
#[Broadcast(topics: ['@="mapAvailableActivities_" ~ entity.getMap().getId()'], private: true)]
class MapAvailableActivity implements ConsumableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Map::class, inversedBy: 'availableActivities')]
    private Map $map;

    #[ORM\Column(enumType: ActivityType::class)]
    private ActivityType $type;

    #[ORM\Column(type: 'float')]
    private float $quantity;

    #[ORM\ManyToOne(targetEntity: MapResource::class, inversedBy: 'spots')]
    #[ORM\JoinColumn(nullable: true)]
    private ?MapResource $mapResource = null;

    #[ORM\Column]
    private string $icon;

    #[ORM\Column]
    private string $name;

    public function __construct(Map $map, ActivityType $type, float $quantity)
    {
        $this->map = $map;
        $this->type = $type;
        $this->quantity = $quantity;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?ActivityType
    {
        return $this->type;
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

    public function getMapResource(): ?MapResource
    {
        return $this->mapResource;
    }

    public function setMapResource(?MapResource $mapResource): void
    {
        $this->mapResource = $mapResource;
    }

    public function getMap(): Map
    {
        return $this->map;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function isEmpty(): bool
    {
        return bccomp($this->quantity, 0.0, 2) === 0;
    }
}
