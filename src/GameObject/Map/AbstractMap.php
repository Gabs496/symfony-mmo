<?php

namespace App\GameObject\Map;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract readonly class AbstractMap implements MapInterface
{
    protected string $description;

    /**
     * @param string $id
     * @param string $name
     * @param float $coordinateX
     * @param float $coordinateY
     */
    public function __construct(
        protected string $id,
        protected string $name,
        protected float $coordinateX,
        protected float $coordinateY,
    )
    {
        $this->description = '';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCoordinateX(): float
    {
        return $this->coordinateX;
    }

    public function getCoordinateY(): float
    {
        return $this->coordinateY;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAvailableActivities(): Collection
    {
        return new ArrayCollection();
    }
}