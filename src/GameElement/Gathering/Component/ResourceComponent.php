<?php

namespace App\GameElement\Gathering\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class ResourceComponent implements GameComponentInterface
{
    public function __construct(
        private float  $gatheringDifficulty,
        private string $involvedMastery,
    ){

    }

    public function getGatheringDifficulty(): float
    {
        return $this->gatheringDifficulty;
    }

    public function getInvolvedMastery(): string
    {
        return $this->involvedMastery;
    }

    public static function getId(): string
    {
        return "resource_component";
    }
}