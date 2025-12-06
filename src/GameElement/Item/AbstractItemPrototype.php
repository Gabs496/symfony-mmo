<?php

namespace App\GameElement\Item;

use App\Entity\Core\GameObject;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use App\GameElement\Render\Component\RenderComponent;

abstract class AbstractItemPrototype implements GameObjectPrototypeInterface
{
    public function make(
        array $components = [],
        string $name = 'Item',
        string $description = '',
    ): GameObject
    {
        $gameObject = new GameObject($this, $components);
        $gameObject->setComponent(new RenderComponent(
            name: $name,
            description: $description,
            iconPath: '/items/' . strtolower($this::getId()) . '.png'
        ));
        return $gameObject;
    }

    public abstract static function getId(): string;
}