<?php

namespace App\Twig\Components\Render;

use App\GameElement\Crafting\AbstractItemRecipe;
use PennyPHP\Core\Engine\GameObjectEngine;
use PennyPHP\Core\GameObjectInterface;
use PennyPHP\Core\GameObjectPrototypeInterface;
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

    public function getItem(): GameObjectPrototypeInterface
    {
        return $this->recipe->getItem();
    }
}