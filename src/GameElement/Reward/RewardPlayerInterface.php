<?php

namespace App\GameElement\Reward;

interface RewardPlayerInterface extends RewardInterface
{
    public function getPlayerCharacterId(): string;
}