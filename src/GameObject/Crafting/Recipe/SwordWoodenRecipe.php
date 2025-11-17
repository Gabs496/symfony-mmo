<?php

namespace App\GameObject\Crafting\Recipe;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\RecipeIngredient;
use App\GameElement\Item\Reward\ItemRuntimeCreatedReward;
use App\GameObject\Item\Equipment\Sword\WoodenSwordPrototype;
use App\GameObject\Item\Resource\Log\ChestnutLogPrototype;
use App\GameObject\Mastery\Crafting\SwordCrafting;

class SwordWoodenRecipe extends AbstractRecipe
{
    public const string ID = 'SWORD_WOODEN_RECIPE';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Wooden Sword',
            description: 'A simple sword made of chestnut wood.',
            ingredients: [
                new RecipeIngredient(ChestnutLogPrototype::ID, 10),
            ],
            craftingTime: 5.0,
            requirements: [
            ],
            rewards: [
                new MasteryReward(masteryId: SwordCrafting::getId(), experience: 0.01),
                new ItemRuntimeCreatedReward(itemPrototypeId: WoodenSwordPrototype::ID, quantity: 1),
            ]
        );
    }
}