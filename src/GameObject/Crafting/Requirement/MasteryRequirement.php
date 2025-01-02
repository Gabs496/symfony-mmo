<?php

namespace App\GameObject\Crafting\Requirement;

use App\GameElement\Crafting\RecipeRequirmentInterface;
use App\GameElement\Mastery\MasteryType;

readonly class MasteryRequirement implements RecipeRequirmentInterface
{
    public function __construct(
        private MasteryType $masteryType,
        private float $experience
    )
    {
    }

    public function getMasteryType(): MasteryType
    {
        return $this->masteryType;
    }

    public function getExperience(): float
    {
        return $this->experience;
    }
}