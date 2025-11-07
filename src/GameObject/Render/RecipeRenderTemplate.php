<?php

namespace App\GameObject\Render;

use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObject\GameObjectPrototypeInterface;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Gathering\Reward\ItemReward;
use App\GameElement\Item\Component\StackComponent;
use RuntimeException;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'Render:RecipeRenderTemplate', template: 'components/Render/RecipeRenderTemplate.html.twig')]
class RecipeRenderTemplate
{
    public AbstractRecipe $recipe;

    public function __construct(
        protected GameObjectEngine $gameObjectEngine,
    )
    {
    }

    public function getItem(): GameObjectPrototypeInterface
    {
        foreach ($this->recipe->getRewards() as $reward) {
            if ($reward instanceof ItemReward) {
                $item = $this->gameObjectEngine->getPrototype($reward->getItemPrototypeId());
                return new $item();
            }
        }

        throw new RuntimeException(sprintf("Recipe %s does not have item reward", $this->recipe::class));
    }

    public function getIngredients(): iterable
    {
        foreach ($this->recipe->getIngredients() as $ingredient) {
            $item = $this->gameObjectEngine->getPrototype($ingredient->getItemPrototypeId());
            $item->setComponent(new StackComponent($ingredient->getQuantity()));
            yield $item;
        }
    }
}