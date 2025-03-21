<?php

namespace App\Engine\Item\Action;

abstract readonly class AbstractAvailableAction
{
    public function __construct(
        protected string $verb,
        protected string $description = '',
    ){
    }

    public function getVerb(): string
    {
        return $this->verb;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}