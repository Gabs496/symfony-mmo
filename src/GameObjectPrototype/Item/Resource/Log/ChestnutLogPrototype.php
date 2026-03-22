<?php

namespace App\GameObjectPrototype\Item\Resource\Log;

use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Render\Component\RenderComponent;
use PennyPHP\Core\AbstractGameObjectPrototype;

#[RenderComponent(
    name: 'Chestnut Log',
    description: 'A log from a chestnut tree.',
    iconPath: '/items/resource_log_chestnut.png'
)]
#[ItemComponent(weight: 0.1)]
class ChestnutLogPrototype extends AbstractGameObjectPrototype
{
    public const string ID = 'LOG_CHESTNUT';

    public static function getType(): string
    {
        return self::ID;
    }
}