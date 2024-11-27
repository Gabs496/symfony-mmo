<?php

namespace App\Entity\Game;

use App\Repository\Game\MapRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MapRepository::class)]
class Map
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    private ?string $name = null;

    #[ORM\Column]
    private ?float $coordinateX = null;

    #[ORM\Column]
    private ?float $coordinateY = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCoordinateX(): ?float
    {
        return $this->coordinateX;
    }

    public function setCoordinateX(float $coordinateX): static
    {
        $this->coordinateX = $coordinateX;

        return $this;
    }

    public function getCoordinateY(): ?float
    {
        return $this->coordinateY;
    }

    public function setCoordinateY(float $coordinateY): static
    {
        $this->coordinateY = $coordinateY;

        return $this;
    }
}
