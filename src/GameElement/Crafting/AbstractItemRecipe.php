<?php

namespace App\GameElement\Crafting;

use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Reward\RewardInterface;

abstract class AbstractItemRecipe
{
    public function __construct(
        protected string              $id,
        protected string              $name,
        protected string              $description,
        protected GameObjectInterface $item,
        /** @var RecipeIngredient[] */
        protected array               $ingredients,
        /** In seconds */
        protected float               $craftingTime,
        /** @var RecipeRequirmentInterface[] */
        protected array               $requirements,
        /** @var RewardInterface[] */
        protected array               $rewards,

    )
    {
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

    public function getItem(): GameObjectInterface
    {
        return $this->item;
    }

    public abstract static function getId(): string;
}