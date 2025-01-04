<?php

namespace App\Core;

use Attribute;

#[Attribute]
readonly class Engine
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