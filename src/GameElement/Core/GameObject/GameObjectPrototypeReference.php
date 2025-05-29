<?php

namespace App\GameElement\Core\GameObject;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class GameObjectPrototypeReference
{
    public function __construct(
        private string $class,
        private string $objectPrototypeIdProperty,
    ) {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getObjectPrototypeIdProperty(): string
    {
        return $this->objectPrototypeIdProperty;
    }
}