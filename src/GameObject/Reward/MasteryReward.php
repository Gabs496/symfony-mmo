<?php

namespace App\GameObject\Reward;

use App\GameElement\Mastery\MasteryType;
use App\GameElement\Reward\RewardInterface;

readonly class MasteryReward implements RewardInterface
{
    public function __construct(
        private MasteryType $type,
        private float       $experience,
    )
    {}

    public function getType(): MasteryType
    {
        return $this->type;
    }

    public function getExperience(): float
    {
        return $this->experience;
    }

}