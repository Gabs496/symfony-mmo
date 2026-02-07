<?php

namespace App\GameElement\Reward;

use PennyPHP\Core\GameObject\GameObjectInterface;

class RewardApply
{
    protected ?GameObjectInterface $recipe;
    protected readonly string $recipeToken;
    public function __construct(
        private readonly RewardInterface $reward,
        GameObjectInterface    $recipe,
    )
    {
        $this->recipe = $recipe;
        $this->recipeToken = $this->recipe->getId();
    }

    public function getReward(): RewardInterface
    {
        return $this->reward;
    }

    public function getRecipeToken(): string
    {
        return $this->recipeToken;
    }

    public function getRecipe(): GameObjectInterface
    {
        return $this->recipe;
    }

    public function setRecipe(GameObjectInterface $recipe): void
    {
        $this->recipe = $recipe;
    }

    public function clear(): void
    {
        $this->recipe = null;
    }
}