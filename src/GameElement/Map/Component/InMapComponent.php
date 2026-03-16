<?php

namespace App\GameElement\Map\Component;

use App\GameElement\Map\Repository\InMapRepository;
use Attribute;
use Doctrine\ORM\Mapping as ORM;
use PennyPHP\Core\Entity\GameComponent;

#[Attribute(Attribute::TARGET_CLASS)]
#[ORM\Entity(repositoryClass: InMapRepository::class)]
#[ORM\Table(name: 'map_in_component')]
#[ORM\Index(fields: ['mapId'])]
#[ORM\Index(fields: ['place'])]
class InMapComponent extends GameComponent
{
    #[ORM\Column]
    protected string $mapId;

    public function __construct(
        MapComponent|string $mapComponent,
        #[ORM\Column]
        protected string $place,
    )
    {
        $this->mapId = is_string($mapComponent) ? $mapComponent : $mapComponent->getGameObject()->getId();
        parent::__construct();
    }

    public function getMapId(): string
    {
        return $this->mapId;
    }

    public function setMapId(string $mapId): void
    {
        $this->mapId = $mapId;
    }

    public function getPlace(): string
    {
        return $this->place;
    }

    public function setPlace(string $place): void
    {
        $this->place = $place;
    }
}