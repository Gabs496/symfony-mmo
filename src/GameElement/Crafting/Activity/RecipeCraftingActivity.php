<?php

namespace App\GameElement\Crafting\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Reward\RewardInterface;

class RecipeCraftingActivity extends AbstractActivity
{
    public function __construct(
        GameObjectInterface $subject,
        private readonly AbstractRecipe $recipe,
    )
    {
        parent::__construct($subject);
    }

    public function getRecipe(): AbstractRecipe
    {
        return $this->recipe;
    }

    /** @return RewardInterface[] */
    public function getRewards(): array
    {
        return $this->recipe->getRewards();
    }
}