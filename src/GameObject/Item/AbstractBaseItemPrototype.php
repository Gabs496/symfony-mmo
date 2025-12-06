<?php

namespace App\GameObject\Item;

use App\Entity\Core\GameObject;
use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Item\Component\ItemWeightComponent;
use App\GameElement\Item\Component\StackComponent;
use App\GameElement\Item\Render\ItemBagRenderTemplateComponent;
use App\GameElement\Map\Render\MapRenderTemplateComponent;

abstract class AbstractBaseItemPrototype extends AbstractItemPrototype
{
    public function make(
        array $components = [],
        string $name = 'Item',
        string $description = '',
        float $weight = 1.0,
    ): GameObject
    {
        $gameObject = parent::make(
            components: $components,
            name: $name,
            description: $description,
        );
        $gameObject
            ->setComponent(new ItemWeightComponent($weight))
            ->setComponent(new MapRenderTemplateComponent(
                template: 'Render:MapRenderTemplate',
            ))
            ->setComponent(new ItemBagRenderTemplateComponent(
                template: 'Render:ItemBagRenderTemplate',
            ))
            ->setComponent(new StackComponent(1))
        ;
        return $gameObject;

    }
}