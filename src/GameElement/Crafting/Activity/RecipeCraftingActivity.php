<?php

namespace App\GameElement\Crafting\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\BaseActivity;
use App\GameElement\Crafting\AbstractRecipe;

#[Activity(id: 'RECIPE_CRAFTING')]
class RecipeCraftingActivity extends BaseActivity
{
    public function __construct(
        private readonly AbstractRecipe $recipe,
    )
    {
    }

    public function getRecipe(): AbstractRecipe
    {
        return $this->recipe;
    }

    public function getRewards(): iterable
    {
        yield from $this->recipe->getRewards();
    }
}