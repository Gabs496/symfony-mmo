<?php

namespace App\GameElement\Crafting\Engine;

use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;

readonly class CraftingEngine
{
    public function __construct(
        private RewardEngine $rewardEngine,
    )
    {}

    public function reward(RecipeCraftingActivity $activity): void
    {
        foreach ($activity->getRewards() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $activity->getSubject()));
        }
    }
}