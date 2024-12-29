<?php

namespace App\GameObject\Map;

interface MapInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getCoordinateX(): float;

    public function getCoordinateY(): float;

    public function getDescription(): string;
}