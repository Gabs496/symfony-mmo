<?php

namespace App\GameElement\Gathering;

use App\GameElement\Core\GameObject\AbstractGameObject;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Mastery\MasteryType;

readonly class AbstractResource extends AbstractGameObject
{
    public function __construct(
        string      $id,
        private string      $name,
        private float       $difficulty,
        private MasteryType $involvedMastery,
        private AbstractItem      $rewardItem,
        private float       $gatheringTime,
    )
    {
        parent::__construct($id);
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

    public function getRewardItem(): AbstractItem
    {
        return $this->rewardItem;
    }

    public function getGatheringTime(): float
    {
        return $this->gatheringTime;
    }
}