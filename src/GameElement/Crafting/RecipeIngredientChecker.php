<?php

namespace App\GameElement\Crafting;

class RecipeIngredientChecker
{
    private array $matches;
    public function __construct(
        AbstractItemRecipe     $recipe,
        /** @var array<CraftingIngredient> */
        private readonly array $ingredients,
    )
    {
        $this->matches = [];
        foreach ($recipe->getIngredients() as $ingredient) {
            $prototype = $ingredient->getPrototype();
            $this->matches[$prototype::getType()] = ['prototype' =>$prototype, 'remaining' => $ingredient->getQuantity()];
        }
    }

    public function match(): bool
    {
        foreach ($this->matches as $type => $match) {
            foreach ($this->ingredients as $ingredient) {
                if ($ingredient->getGameObject()->isInstanceOf($type)) {
                    $match['remaining'] -= $ingredient->getQuantity();
                }
            }
        }

        foreach ($this->matches as $type => $match) {
            if ($match['remaining'] > 0) {
                return false;
            }
        }

        return true;
    }
}