<?php

namespace App\Entity\Game;

use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameElement\Mob\AbstractMob;
use App\GameElement\Mob\AbstractMobInstance;
use App\Repository\Game\MapSpawnedMobRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: MapSpawnedMobRepository::class)]
#[Broadcast(topics: ['@="map_spawned_mobs_" ~ entity.getMapId()'], private: true, template: 'map/spawned_mob_list.stream.html.twig')]
class MapSpawnedMob extends AbstractMobInstance
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(length: 50)]
    private ?string $mapId = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $mobId = null;

    #[ORM\Column(type: 'float')]
    protected float $currentHealth = 0.0;

    #[GameObjectReference(class: AbstractMob::class,objectIdProperty: 'mobId')]
    protected AbstractMob $mob;

    public function __construct(AbstractMob $mob)
    {
        $this->id = Uuid::v7();
        parent::__construct($mob);
    }

    public function getId(): ?string
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
