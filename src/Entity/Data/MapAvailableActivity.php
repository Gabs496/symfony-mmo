<?php

namespace App\Entity\Data;

use App\Entity\Game\MapResource;
use App\GameElement\Activity\ActivityAvailable;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Activity\ActivityInvolvableInterface;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;
use App\Interface\ConsumableInterface;
use App\Repository\Data\MapAvailableActivityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MapAvailableActivityRepository::class)]
#[Broadcast(topics: ['@="mapAvailableActivities_" ~ entity.getMapId()'], private: true, template: 'map/MapAvailableActivity.stream.html.twig')]
#[ActivityAvailable(ResourceGatheringActivity::class, as: ActivityAvailable::AS_DIRECT_OBJECT)]
class MapAvailableActivity implements ConsumableInterface, ActivityInvolvableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $mapId;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'float')]
    private float $quantity;

    #[ORM\ManyToOne(targetEntity: MapResource::class, inversedBy: 'spots', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    private ?MapResource $mapResource = null;

    #[ORM\Column]
    private string $icon;

    #[ORM\Column]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Activity::class, cascade: ['persist'], inversedBy: 'mapAvailableActivities')]
    private ?Activity $involvingActivity = null;

    public function __construct(string $mapId, string $type, float $quantity)
    {
        $this->mapId = $mapId;
        $this->type = $type;
        $this->quantity = $quantity;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?string
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

    public function getMapId(): ?string
    {
        return $this->mapId;
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

    /**
     * @psalm-return Activity|null
     */
    public function getInvolvingActivity(): ?ActivityInterface
    {
        return $this->involvingActivity;
    }

    /**
     * @psalm-param Activity|null $involvingActivity
     */
    public function setInvolvingActivity(?ActivityInterface $involvingActivity): void
    {
        $this->involvingActivity = $involvingActivity;
        $involvingActivity?->getMapAvailableActivities()->add($this);
    }

    public function startActivity(ActivityInterface $activity): void
    {
        $this->setInvolvingActivity($activity);
    }

    public function endActivity(ActivityInterface $activity): void
    {
        $this->setInvolvingActivity(null);
    }

    public function isInvolvedInActivity(?ActivityInterface $activity = null): bool
    {
        if (!$activity) {
            return (bool)$this->involvingActivity;
        }

        return $this->involvingActivity === $activity;
    }
}
