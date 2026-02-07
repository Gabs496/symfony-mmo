<?php

namespace App\GameElement\Map\Component;

use PennyPHP\Core\GameComponent\Entity\GameComponent;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use App\GameElement\Render\Component\RenderComponent;
use Attribute;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[Attribute(Attribute::TARGET_CLASS)]
#[ORM\Entity]
class MapComponent extends GameComponent
{
    /** @var Collection<int, ObjectSpawn> */
    #[ORM\OneToMany(targetEntity: ObjectSpawn::class, mappedBy: 'mapComponent', cascade: ['all'], orphanRemoval: true)]
    protected Collection $spawns;

    public function __construct(
        #[ORM\Column]
        protected float $coordinateX,
        #[ORM\Column]
        protected float $coordinateY,
        /** @var array<ObjectSpawn> $spawns */
        array $spawns =[],
    )
    {

        foreach ($spawns as $spawn) {
            $spawn->setMapComponent($this);
        }
        $this->spawns = new ArrayCollection($spawns);
        parent::__construct();
    }

    public function getCoordinateX(): float
    {
        return $this->coordinateX;
    }

    public function getCoordinateY(): float
    {
        return $this->coordinateY;
    }

    public function getSpawns(): Collection
    {
        return $this->spawns;
    }

    public function getName(): string
    {
        return $this->gameObject->getComponent(RenderComponent::class)->getName();
    }
}