<?php

namespace App\GameElement\Crafting\Event;

use App\GameElement\Activity\ActivitySubjectInterface;
use App\GameElement\Crafting\AbstractRecipe;

class BeforeCraftingTakeIngredientEvent
{
    private bool $processed = false;

    public function __construct(
        private readonly ActivitySubjectInterface $subject,
        private readonly AbstractRecipe           $recipe,
    ){}

    public function getSubject(): ActivitySubjectInterface
    {
        return $this->subject;
    }

    public function getRecipe(): AbstractRecipe
    {
        return $this->recipe;
    }

    public function setProcessed(bool $processed = true): void
    {
        $this->processed = $processed;
    }

    public function isProcessed(): bool
    {
        return $this->processed;
    }
}