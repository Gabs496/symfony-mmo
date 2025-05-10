<?php

namespace App\GameObject\Item;

abstract readonly class AbstractItemResourcePrototype extends AbstractBaseItemPrototype
{
    public function __construct(
        string $id,
        string $name,
        string $description,
        float $weight,
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            stackable: true,
            weight: $weight,
        );
    }
}