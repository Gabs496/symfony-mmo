<?php

namespace App\GameObject\Item;

abstract class AbstractItemResourcePrototype extends AbstractBaseItemPrototype
{
    public function __construct(
        string $id,
        string $name,
        string $description,
        float $weight,
        array $components,
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            weight: $weight,
            components: $components,
        );
    }
}