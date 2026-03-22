<?php

namespace App\GameElement\Gathering;

use App\GameElement\Reward\RewardInterface;

interface GatherRewardsInterface
{
    /** @return array<RewardInterface> */
    public function getGatherRewards(): array;
}