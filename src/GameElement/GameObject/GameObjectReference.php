<?php

namespace App\GameElement\GameObject;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class GameObjectReference
{
    public function __construct(
        private string $collectionId,
        private string $objectIdProperty,
    ) {
    }

    public function getCollectionId(): string
    {
        return $this->collectionId;
    }

    public function getObjectIdProperty(): string
    {
        return $this->objectIdProperty;
    }
}