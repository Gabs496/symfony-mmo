<?php

namespace App\GameElement\Reward;

use ArrayObject;

interface RewardInterface
{
    public function getName(): string;

    public function getQuantity(): float;

    public function getAttributes(): ArrayObject;
}