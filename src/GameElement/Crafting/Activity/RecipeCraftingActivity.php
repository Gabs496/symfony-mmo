<?php

namespace App\GameElement\Crafting\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Core\Token\TokenizableInterface;
use App\GameElement\Crafting\AbstractRecipe;

#[Activity(id: 'RECIPE_CRAFTING')]
class RecipeCraftingActivity extends AbstractActivity
{
    public function __construct(
        TokenizableInterface $subject,
        private readonly AbstractRecipe $recipe,
    )
    {
        parent::__construct($subject);
        $this->duration = $this->recipe->getCraftingTime();
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