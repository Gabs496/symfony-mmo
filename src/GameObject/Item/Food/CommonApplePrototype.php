<?php

namespace App\GameObject\Item\Resource\Food;

use App\GameObject\Item\AbstractItemResourcePrototype;

class CommonApplePrototype extends AbstractItemResourcePrototype
{
    public const string ID = 'RESOURCE_FOOD_COMMON_APPLE';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Apple',
            description: 'A common apple, perfect for a quick snack or to restore a small amount of health.',
            weight: 0.1,
        );
    }
}