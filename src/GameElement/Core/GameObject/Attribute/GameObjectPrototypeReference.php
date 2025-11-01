<?php

namespace App\GameElement\Core\GameObject\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class GameObjectPrototypeReference
{
    public function __construct(
        private string $objectPrototypeIdProperty,
    ) {
    }

    public function getObjectPrototypeIdProperty(): string
    {
        return $this->objectPrototypeIdProperty;
    }
}