<?php

namespace App\GameTask\Message;

use App\Entity\MasteryType;

readonly class RewardMastery implements RewardPlayerCharacterInterface
{
    public function __construct(
        private string      $playerCharacterId,
        private MasteryType $type,
        private float       $experience
    )
    {}

    public function getPlayerCharacterId(): string
    {
        return $this->playerCharacterId;
    }

    public function getType(): MasteryType
    {
        return $this->type;
    }

    public function getExperience(): float
    {
        return $this->experience;
    }

}