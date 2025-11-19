<?php

namespace App\GameObject\Item;

use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Item\Component\ItemWeightComponent;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\Item\Render\ItemBagRenderComponent;
use App\GameElement\Map\Render\MapRenderComponent;

abstract class AbstractBaseItemPrototype extends AbstractItemPrototype
{
    public function __construct(
        string $id,
        string $name,
        string $description,
        float $weight,
        array $components = [],
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            components: array_merge([
                new ItemWeightComponent($weight),
                new MapRenderComponent(
                    template: 'Render:MapRenderTemplate',
                    name: $name,
                    description: $description,
                    iconPath: '/items/' . strtolower($id) . '.png'
                ),
                new ItemBagRenderComponent(
                    template: 'Render:ItemBagRenderTemplate',
                    name: $name,
                    description: $description,
                    iconPath: '/items/' . strtolower($id) . '.png'
                ),
                new StackComponent(1),
            ], $components)
        );
    }
}