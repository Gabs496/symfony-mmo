<?php

namespace App\GameElement;

use Attribute;

#[Attribute]
readonly class Map implements GameElementInterface
{
    public function __construct(
        protected string $id,
        protected string $name,
        protected float $coordinateX,
        protected float $coordinateY,
        protected string $description = ''
    )
    {
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
}