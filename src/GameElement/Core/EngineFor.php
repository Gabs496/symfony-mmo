<?php

namespace App\GameElement\Core;

use Attribute;

#[Attribute]
readonly class EngineFor
{
    public function __construct(
        public string $id,
    )
    {}

    public function getId(): string
    {
        return $this->id;
    }
}