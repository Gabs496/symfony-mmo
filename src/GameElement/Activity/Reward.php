<?php

namespace App\GameElement\Activity;

use App\GameElement\Reward\RewardInterface;
use Attribute;

#[Attribute]
readonly class Reward
{
    public function __construct(
        private RewardInterface $reward,
    )
    {
    }

    public function getReward(): RewardInterface
    {
        return $this->reward;
    }
}