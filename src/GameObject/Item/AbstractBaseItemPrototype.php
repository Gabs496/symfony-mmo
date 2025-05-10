<?php

namespace App\GameObject\Item;

use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Item\Component\ItemWeightComponent;

readonly abstract class AbstractBaseItemPrototype extends AbstractItemPrototype
{
    public function __construct(
        string $id,
        string $name,
        string $description,
        bool $stackable,
        float $weight,
        array $components = [],
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            stackable: $stackable,
            components: array_merge([
                new ItemWeightComponent($weight),
            ], $components)
        );
    }
}