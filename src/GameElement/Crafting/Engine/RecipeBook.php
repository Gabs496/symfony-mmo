<?php

namespace App\GameElement\Crafting\Engine;

use App\GameElement\Crafting\AbstractItemRecipe;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class RecipeBook
{
    public function __construct(
        /** @var iterable<AbstractItemRecipe> */
        #[AutowireIterator('crafting.item_recipe')]
        private iterable $recipes,
    )
    {
    }

    public function getRecipes(): iterable
    {
        return $this->recipes;
    }

    public function getRecipe(string $recipeId): AbstractItemRecipe
    {
        foreach ($this->recipes as $recipe) {
            if ($recipe->getId() === $recipeId) {
                return $recipe;
            }
        }

        throw new InvalidArgumentException("Recipe id '$recipeId' not found");
    }
}