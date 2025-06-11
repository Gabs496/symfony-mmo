<?php

namespace App\GameObject\Item\Food;

use App\GameElement\Healing\Component\Healing;
use App\GameObject\Item\AbstractItemFoodPrototype;

class CommonApplePrototype extends AbstractItemFoodPrototype
{
    public const string ID = 'RESOURCE_FOOD_COMMON_APPLE';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Apple',
            description: 'A common apple, perfect for a quick snack or to restore a small amount of health.',
            weight: 0.05,
            components: [
                new Healing(0.05),
            ],
        );
    }
}