<?php

namespace App\GameObject\Resource;

use App\Entity\MasteryType;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class AsResource
{
    public function __construct(
        private string      $id,
        private string      $name,
        private float       $difficulty,
        private MasteryType $involvedMastery,
        private string      $rewardItemId,
        private float       $gatheringTime,
    )
    {

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDifficulty(): float
    {
        return $this->difficulty;
    }

    public function getInvolvedMastery(): MasteryType
    {
        return $this->involvedMastery;
    }

    public function getRewardItemId(): string
    {
        return $this->rewardItemId;
    }

    public function getGatheringTime(): float
    {
        return $this->gatheringTime;
    }
}