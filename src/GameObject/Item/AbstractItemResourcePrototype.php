<?php

namespace App\GameObject\Item;

use App\GameElement\Gathering\GatherableInterface;

abstract class AbstractItemResourcePrototype extends AbstractBaseItemPrototype implements GatherableInterface
{
    public function __construct(
        string $id,
        string $name,
        string $description,
        float $weight,
        array $components = [],
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