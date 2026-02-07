<?php

namespace App\GameElement\Crafting\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use PennyPHP\Core\GameObject\Engine\GameObjectEngine;
use PennyPHP\Core\GameObject\GameObjectInterface;
use App\GameElement\Crafting\AbstractItemRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Crafting\Exception\IngredientNotAvailableException;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Item\ItemEngineInterface;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Reward\Engine\RewardEngine;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class CraftingEngine
{
    public function __construct(
        private RewardEngine $rewardEngine,
        private ActivityEngine $activityEngine,
        /** @var iterable<AbstractItemRecipe> */
        #[AutowireIterator('crafting.item_recipe')]
        private iterable $recipes,
        private GameObjectEngine $gameObjectEngine,
    )
    {}

    /**
     * @throws IngredientNotAvailableException
     */
    public function startCrafting(GameObjectInterface $subject, AbstractItemRecipe|string $recipe, ItemEngineInterface $itemEngine): void
    {
        if (is_string($recipe)) {
            $recipe = $this->getRecipe($recipe);
        }

        try {
            foreach ($recipe->getIngredients() as $ingredient) {
                $item = $this->gameObjectEngine->getPrototype($ingredient->getItemPrototypeId());
                $itemEngine->take($subject, $item, $ingredient->getQuantity());
            }
        } catch (ItemQuantityNotAvailableException $event) {
            throw new IngredientNotAvailableException('Recipe ingredients not availables', 0, $event);
        }

        $this->activityEngine->run(new RecipeCraftingActivity($subject, $recipe));
    }

    public function craft(GameObjectInterface $subject, AbstractItemRecipe $recipe): void
    {
        $this->rewardEngine->apply(new ItemReward($recipe->getItem()), $subject);

        foreach ($recipe->getRewards() as $reward) {
            $this->rewardEngine->apply($reward, $subject);
        }
    }

    public function getRecipes(): iterable
    {
        return $this->recipes;
    }

    public function getRecipe(string $recipeId): AbstractItemRecipe
    {
        foreach ($this->recipes as $recipe) {
            if ($recipe->getId() === $recipeId) {
                return $recipe;
            }
        }

        throw new InvalidArgumentException("Recipe id '$recipeId' not found");
    }
}