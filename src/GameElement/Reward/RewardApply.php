<?php

namespace App\GameElement\Reward;

readonly class RewardApply
{
    public function __construct(
        private RewardInterface $reward,
        private RewardRecipe    $recipe,
    )
    {
    }

    public function getReward(): RewardInterface
    {
        return $this->reward;
    }

    public function getRecipe(): RewardRecipe
    {
        return $this->recipe;
    }
}