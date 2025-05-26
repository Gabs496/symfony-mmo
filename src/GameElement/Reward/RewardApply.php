<?php

namespace App\GameElement\Reward;

use App\GameElement\Core\Token\TokenInterface;
use App\GameElement\Core\Token\TokenizableInterface;

class RewardApply
{
    protected ?TokenizableInterface $recipe;
    protected readonly TokenInterface $recipeToken;
    public function __construct(
        private readonly RewardInterface $reward,
        TokenizableInterface    $recipe,
    )
    {
        $this->recipe = $recipe;
        $this->recipeToken = $this->recipe->getToken();
    }

    public function getReward(): RewardInterface
    {
        return $this->reward;
    }

    public function getRecipeToken(): TokenInterface
    {
        return $this->recipeToken;
    }

    public function getRecipe(): TokenizableInterface
    {
        return $this->recipe;
    }

    public function setRecipe(TokenizableInterface $recipe): void
    {
        $this->recipe = $recipe;
    }

    public function clear(): void
    {
        $this->recipe = null;
    }
}