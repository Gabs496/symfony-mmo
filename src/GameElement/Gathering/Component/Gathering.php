<?php

namespace App\GameElement\Gathering\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

readonly class Gathering implements GameComponentInterface
{
    public function __construct(
        private float  $difficulty,
        private string $involvedMastery,
        private float  $gatheringTime
    ){

    }

    public function getDifficulty(): float
    {
        return $this->difficulty;
    }

    public function getInvolvedMastery(): string
    {
        return $this->involvedMastery;
    }

    public function getGatheringTime(): float
    {
        return $this->gatheringTime;
    }
}