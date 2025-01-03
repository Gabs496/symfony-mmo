<?php

namespace App\GameObject\Activity;

use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Crafting\AbstractRecipe;

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