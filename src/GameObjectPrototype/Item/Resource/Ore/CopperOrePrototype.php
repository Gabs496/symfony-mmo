<?php

namespace App\GameObjectPrototype\Item\Resource\Ore;

use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Render\Component\RenderComponent;
use PennyPHP\Core\AbstractGameObjectPrototype;

#[RenderComponent(
    name: 'Coppper Ore',
    description: 'A piece of copper ore.',
    iconPath: '/items/resource_ore_copper.png'
)]
#[ItemComponent(weight: 0.2)]
class CopperOrePrototype extends AbstractGameObjectPrototype
{
    public const string ID = 'ORE_COPPER';

    public static function getType(): string
    {
        return self::ID;
    }
}