<?php

namespace App\GameElement\Crafting\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Crafting\AbstractRecipe;

#[Activity(id: 'RECIPE_CRAFTING')]
readonly class RecipeCraftingActivity implements ActivityInterface
{
    public function __construct(
        private string $playerId,
        private AbstractRecipe $recipe,
    )
    {
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function getRecipe(): AbstractRecipe
    {
        return $this->recipe;
    }
}