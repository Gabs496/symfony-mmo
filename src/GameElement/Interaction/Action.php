<?php

namespace App\GameElement\Interaction;

readonly class Action
{
    public function __construct(
        private string $id,
        private array $parameters = [],
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}