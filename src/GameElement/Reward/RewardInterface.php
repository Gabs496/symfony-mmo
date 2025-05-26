<?php

namespace App\GameElement\Reward;

interface RewardInterface
{
    public function getName(): string;

    public function getQuantity(): float;

    public function getAttributes(): array;
}