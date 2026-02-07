<?php

namespace App\GameElement\Gathering;

use App\GameElement\Reward\RewardInterface;

interface GatherableInterface
{
    /** @return array<RewardInterface> */
    public function getGatherRewards(): array;
}