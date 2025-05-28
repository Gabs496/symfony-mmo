<?php

namespace App\GameObject\Item\Resource\Log;

use App\GameObject\Item\AbstractItemResourcePrototype;

class ChestnutLogPrototype extends AbstractItemResourcePrototype
{
    public const string ID = 'RESOURCE_LOG_CHESTNUT';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Chestnut Log',
            description: 'A log from a chestnut tree.',
            weight: 0.1,
        );
    }
}