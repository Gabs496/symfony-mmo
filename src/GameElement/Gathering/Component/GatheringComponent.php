<?php

namespace App\GameElement\Gathering\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Reward\RewardInterface;

readonly class GatheringComponent implements GameComponentInterface
{
    public function __construct(
        private float  $difficulty,
        private string $involvedMastery,
        private float  $gatheringTime,
        /** @var array<int, RewardInterface>|array */
        private array $rewards = [],
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

    public function getRewards(): array
    {
        return $this->rewards;
    }

    public static function getId(): string
    {
        return "gathering_component";
    }
}