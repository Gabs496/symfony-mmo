<?php

namespace App\GameElement\Crafting\Engine;

use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Crafting\AbstractItemRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Reward\Engine\RewardEngine;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class CraftingEngine
{
    public function __construct(
        private RewardEngine $rewardEngine,
        private ActivityEngine $activityEngine,
        /** @var iterable<AbstractItemRecipe> */
        #[AutowireIterator('crafting.item_recipe')]
        private iterable $recipes,
    )
    {}

    public function startCrafting(GameObjectInterface $subject, AbstractItemRecipe $recipe): void
    {
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
}