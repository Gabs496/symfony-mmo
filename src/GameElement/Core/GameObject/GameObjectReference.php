<?php

namespace App\GameElement\Core\GameObject;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class GameObjectReference
{
    public function __construct(
        /** @deprecated Not more useful */
        private string $class,
        private string $objectIdProperty,
    ) {
    }

    /** @deprecated Not more useful */
    public function getClass(): string
    {
        return $this->class;
    }

    public function getObjectIdProperty(): string
    {
        return $this->objectIdProperty;
    }
}