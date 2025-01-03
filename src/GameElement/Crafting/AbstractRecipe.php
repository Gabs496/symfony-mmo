<?php

namespace App\GameElement\Crafting;

use App\GameElement\Reward\RewardInterface;

abstract readonly class AbstractRecipe
{
    public function __construct(
        protected string $id,
        protected string $name,
        protected string $description,
        /** @var RecipeIngredient[] */
        protected array $ingredients,
        /** In seconds */
        protected float $craftingTime,
        /** @var RecipeRequirmentInterface[] */
        protected array $requirements,
        /** @var RewardInterface[] */
        protected array $rewards,

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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function getCraftingTime(): float
    {
        return $this->craftingTime;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function getRewards(): array
    {
        return $this->rewards;
    }
}