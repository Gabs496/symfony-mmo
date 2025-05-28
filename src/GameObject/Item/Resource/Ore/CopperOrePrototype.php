<?php

namespace App\GameObject\Item\Resource\Ore;

use App\GameObject\Item\AbstractItemResourcePrototype;

class CopperOrePrototype extends AbstractItemResourcePrototype
{
    public const string ID = 'RESOURCE_ORE_COPPER';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Coppper Ore',
            description: 'A piece of copper ore.',
            weight: 0.1,
        );
    }
}