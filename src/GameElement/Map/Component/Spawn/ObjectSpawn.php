<?php

namespace App\GameElement\Map\Component\Spawn;

use App\GameElement\Map\Component\MapComponent;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Uid\Uuid;

#[Entity]
#[Table(name: 'map_object_spawn')]
#[InheritanceType('JOINED')]
class ObjectSpawn
{
    #[Id]
    #[Column(type: "guid")]
    private string $id;

    #[ManyToOne(targetEntity: MapComponent::class, inversedBy: "spawns")]
    private MapComponent $mapComponent;

    public function __construct(
        #[Column(length: 50)]
        protected string $prototypeId,
        #[Column]
        protected int    $maxAvailability,
        #[Column]
        protected float  $spawnRate
    )
    {
        $this->id = Uuid::v7();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMapComponent(): MapComponent
    {
        return $this->mapComponent;
    }

    public function setMapComponent(MapComponent $mapComponent): void
    {
        $this->mapComponent = $mapComponent;
    }

    public function getPrototypeId(): string
    {
        return $this->prototypeId;
    }

    public function setPrototypeId(string $prototypeId): void
    {
        $this->prototypeId = $prototypeId;
    }

    public function getMaxAvailability(): int
    {
        return $this->maxAvailability;
    }

    public function setMaxAvailability(int $maxAvailability): void
    {
        $this->maxAvailability = $maxAvailability;
    }

    public function getSpawnRate(): float
    {
        return $this->spawnRate;
    }

    public function setSpawnRate(float $spawnRate): void
    {
        $this->spawnRate = $spawnRate;
    }

}