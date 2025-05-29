<?php

namespace App\GameElement\Map;

use App\GameElement\Core\GameObject\AbstractGameObject;

abstract class AbstractMap extends AbstractGameObject
{
    public function __construct(
        string $id,
        protected string $name,
        protected float $coordinateX,
        protected float $coordinateY,
        protected string $description = '',
        array $components = [],
    )
    {
        parent::__construct($id, $components);
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