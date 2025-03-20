<?php

namespace App\GameObject\Item\Resource;

use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\AvailableAction\Drop;

readonly abstract class AbstractBaseResource extends AbstractItem
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
            availableActions: [
                new Drop()
            ]
        );
    }
}