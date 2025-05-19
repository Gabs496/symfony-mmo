<?php

namespace App\GameElement\Reward;

/**
 * @template T
 */
readonly class RewardApply
{
    /**
     * @param RewardInterface $reward
     * @param T $recipe
     */
    public function __construct(
        private RewardInterface            $reward,
        private RewardRecipeTokenInterface $recipe,
    )
    {
    }

    public function getReward(): RewardInterface
    {
        return $this->reward;
    }

    /**
     * @return T
     */
    public function getRecipe(): RewardRecipeTokenInterface
    {
        return $this->recipe;
    }
}