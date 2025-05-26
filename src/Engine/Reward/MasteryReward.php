<?php

namespace App\Engine\Reward;

use App\Engine\Math;
use App\GameElement\Mastery\MasteryType;
use App\GameElement\Reward\RewardInterface;

readonly class MasteryReward implements RewardInterface
{
    public function __construct(
        private MasteryType $type,
        private float       $experience,
        private array       $attributes = [],
    )
    {
    }

    public function getType(): MasteryType
    {
        return $this->type;
    }

    public function getExperience(): float
    {
        return $this->experience;
    }

    public function getName(): string
    {
        return strtolower($this->type->getName()) . ' experience';
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