<?php

namespace App\GameElement;

use Attribute;

#[Attribute]
class Action
{
    public function __construct(
        protected string $class,
    )
    {

    }

    public function getClass(): string
    {
        return $this->class;
    }
}