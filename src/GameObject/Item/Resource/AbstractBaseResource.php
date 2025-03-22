<?php

namespace App\GameObject\Item\Resource;

use App\GameObject\Item\AbstractBaseItem;

readonly abstract class AbstractBaseResource extends AbstractBaseItem
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