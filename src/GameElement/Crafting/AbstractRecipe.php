<?php

namespace App\GameElement\Crafting;

use App\GameElement\Core\GameObject\AbstractGameObject;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Reward\RewardInterface;

abstract readonly class AbstractRecipe extends AbstractGameObject
{
    public function __construct(
        string $id,
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
        parent::__construct($id);
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

    /** @return RewardInterface[] */
    public function getRewards(): array
    {
        return $this->rewards;
    }

    /** @return ItemReward[] */
    public function getItemRewards(): array
    {
        return array_filter($this->rewards, fn($reward) => $reward instanceof ItemReward);
    }
}