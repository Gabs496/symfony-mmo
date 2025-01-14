<?php

namespace App\GameElement\Mastery;

use App\GameElement\Character\AbstractCharacter;
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
        return strtolower($this->type->__toString()) . ' experience';
    }

    public function getQuantity(): float
    {
        return $this->experience;
    }

    public function getSubject(): AbstractCharacter
    {
        return $this->subject;
    }
}