<?php

namespace App\Entity\Game;

use App\GameElement\Core\GameObject\GameObjectReference;
use App\GameObject\NPC\Mob\BaseMob;
use App\Repository\Game\MapSpawnedMobRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MapSpawnedMobRepository::class)]
class MapSpawnedMob
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

    #[GameObjectReference(class: BaseMob::class,objectIdProperty: 'mobId')]
    private BaseMob $mob;

    public function __construct()
    {
        $this->id = Uuid::v7();
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
