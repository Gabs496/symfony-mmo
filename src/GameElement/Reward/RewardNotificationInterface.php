<?php

namespace App\GameElement\Reward;

interface RewardNotificationInterface
{
    public function getName(): string;
    public function getQuantity(): float;
}