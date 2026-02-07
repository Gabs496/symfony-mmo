<?php

namespace App\GameElement\Crafting\Activity;

use App\GameElement\Activity\AbstractActivity;
use PennyPHP\Core\GameObject\GameObjectInterface;
use App\GameElement\Crafting\AbstractItemRecipe;
use App\GameElement\Reward\RewardInterface;

class RecipeCraftingActivity extends AbstractActivity
{
    public function __construct(
        GameObjectInterface                 $subject,
        private readonly AbstractItemRecipe $recipe,
    )
    {
        parent::__construct($subject);
    }

    public function getRecipe(): AbstractItemRecipe
    {
        return $this->recipe;
    }

    /** @return RewardInterface[] */
    public function getRewards(): array
    {
        return $this->recipe->getRewards();
    }
}