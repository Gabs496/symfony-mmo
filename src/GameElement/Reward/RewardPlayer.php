<?php

namespace App\GameElement\Reward;

readonly class RewardPlayer
{
    public function __construct(
        private string $playerId,
        private RewardInterface $reward,
    )
    {
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function getReward(): RewardInterface
    {
        return $this->reward;
    }
}