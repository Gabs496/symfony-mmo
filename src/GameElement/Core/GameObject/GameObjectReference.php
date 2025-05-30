<?php

namespace App\GameElement\Core\GameObject;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class GameObjectReference
{
    public function __construct(
        private string $objectIdProperty,
    ) {
    }

    public function getObjectIdProperty(): string
    {
        return $this->objectIdProperty;
    }
}