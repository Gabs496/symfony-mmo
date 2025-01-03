<?php

namespace App\Core\GameObject;

readonly abstract class AbstractGameObject
{
    public function __construct(
        protected string $id,
    )
    {}

    public function getId(): string
    {
        return $this->id;
    }
}