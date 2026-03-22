<?php

namespace App\GameObjectPrototype\Item\Food;

use App\GameElement\Healing\Component\HealingComponent;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Render\Component\RenderComponent;
use PennyPHP\Core\AbstractGameObjectPrototype;

#[RenderComponent(
    name: 'Apple',
    description: 'A common apple, perfect for a quick snack or to restore a small amount of health.',
    iconPath: '/items/resource_food_common_apple.png'
)]
#[ItemComponent(weight: 0.5)]
#[HealingComponent(0.05)]
class CommonApplePrototype extends AbstractGameObjectPrototype
{
    public const string ID = 'FOOD_COMMON_APPLE';

    public static function getType(): string
    {
        return self::ID;
    }
}