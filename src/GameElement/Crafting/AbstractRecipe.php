<?php

namespace App\GameElement\Crafting;

use App\GameElement\Reward\RewardInterface;

abstract readonly class AbstractRecipe
{
    public function __construct(
        protected string $id,
        protected string $name,
        protected string $description,
        /** @var RecipeIngredient[] */
        protected array $ingredients,
        /** In seconds */
        protected float $craftingTime,
        /** @var RecipeRequirmentInterface[] */
        protected array $requirements,
        /** @var RewardInterface[] */
        protected array $rewards,

    )
    {
    }
}