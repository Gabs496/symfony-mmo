<?php

namespace App\Engine\Combat;

use App\GameElement\Reward\RewardInterface;

interface RewardOnDefeatInterface
{
    /** @return RewardInterface[] */
    public function getRewardOnDefeats(): array;
}