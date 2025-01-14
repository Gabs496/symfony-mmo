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
        private RewardInterface $reward,
        private object $recipe,
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
    public function getRecipe(): object
    {
        return $this->recipe;
    }
}