<?php

namespace App\GameElement\Crafting\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Crafting\AbstractItemRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Crafting\CraftingIngredient;
use App\GameElement\Crafting\Exception\IngredientNotAvailableException;
use App\GameElement\Crafting\RecipeIngredientChecker;
use App\GameElement\Item\Reward\ItemRuntimeCreatedReward;
use App\GameElement\Reward\Engine\RewardEngine;
use PennyPHP\Core\GameObjectInterface;

readonly class CraftingEngine
{
    public function __construct(
        private RewardEngine   $rewardEngine,
        private ActivityEngine $activityEngine,
        private RecipeBook     $recipeBook,
    )
    {}

    /**
     * @param array<CraftingIngredient> $ingredients
     * @throws IngredientNotAvailableException
     */
    public function startCrafting(GameObjectInterface $subject, AbstractItemRecipe|string $recipe, array $ingredients): void
    {
        if (is_string($recipe)) {
            $recipe = $this->recipeBook->getRecipe($recipe);
        }

        $ingredentsChecker = new RecipeIngredientChecker($recipe,$ingredients);

        if (!$ingredentsChecker->match()) {
            throw new IngredientNotAvailableException('Recipe ingredients not availables');
        }

        $this->activityEngine->run(new RecipeCraftingActivity($subject, $recipe));
    }

    public function craft(GameObjectInterface $subject, AbstractItemRecipe $recipe): void
    {
        $this->rewardEngine->apply(new ItemRuntimeCreatedReward($recipe->getItem()::getType()), $subject);

        foreach ($recipe->getRewards() as $reward) {
            $this->rewardEngine->apply($reward, $subject);
        }
    }
}