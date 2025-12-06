<?php

namespace App\Twig\Components\Render;

use App\GameElement\Core\GameObject\Engine\GameObjectEngine;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Crafting\AbstractItemRecipe;
use App\GameElement\Item\Component\StackComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'Render:RecipeRenderTemplate', template: 'components/Render/RecipeRenderTemplate.html.twig')]
class RecipeRenderTemplate
{
    public AbstractItemRecipe $recipe;

    public function __construct(
        protected GameObjectEngine $gameObjectEngine,
    )
    {
    }

    public function getItem(): GameObjectInterface
    {
        return $this->recipe->getItem();
    }

    public function getIngredients(): iterable
    {
        foreach ($this->recipe->getIngredients() as $ingredient) {
            $item = $this->gameObjectEngine->getPrototype($ingredient->getItemPrototypeId())->make();
            $item->setComponent(new StackComponent($ingredient->getQuantity()));
            yield $item;
        }
    }
}