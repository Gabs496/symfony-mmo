<?php

namespace App\Engine\Player\Reward;

use App\Engine\Math;
use App\GameElement\Mastery\MasteryType;
use App\GameElement\Reward\RewardInterface;
use App\GameElement\Reward\RewardNotificationInterface;

readonly class MasteryReward implements RewardInterface, RewardNotificationInterface
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

    public function getName(): string
    {
        return strtolower($this->type->getName()) . ' experience';
    }

    public function getQuantity(): float
    {
        return Math::getStatViewValue($this->experience);
    }
}