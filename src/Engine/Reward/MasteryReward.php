<?php

namespace App\Engine\Reward;

use App\Engine\Math;
use App\GameElement\Reward\RewardInterface;

readonly class MasteryReward implements RewardInterface
{
    public function __construct(
        private string $masteryId,
        private float  $experience,
        private array  $attributes = [],
    )
    {
    }

    public function getMasteryId(): string
    {
        return $this->masteryId;
    }

    public function getExperience(): float
    {
        return $this->experience;
    }

    public function getQuantity(): float
    {
        return Math::getStatViewValue($this->experience);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}