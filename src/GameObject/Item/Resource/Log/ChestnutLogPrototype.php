<?php

namespace App\GameObject\Item\Resource\Log;

use App\GameObject\Item\AbstractItemResourcePrototype;

class ChestnutLogPrototype extends AbstractItemResourcePrototype
{
    public function __construct()
    {
        parent::__construct(
            id: 'RESOURCE_LOG_CHESTNUT',
            name: 'Chestnut Log',
            description: 'A log from a chestnut tree.',
            weight: 0.1,
        );
    }
}