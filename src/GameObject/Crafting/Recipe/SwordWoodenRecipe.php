<?php

namespace App\GameObject\Crafting\Recipe;

use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\RecipeIngredient;
use App\GameElement\Mastery\MasteryType;
use App\GameObject\Gathering\LogChestnut;
use App\GameObject\Item\Sword\WoodenSword;
use App\GameObject\Reward\ItemReward;
use App\GameObject\Reward\MasteryReward;

readonly class SwordWoodenRecipe extends AbstractRecipe
{

    public const string ID = 'SWORD_WOODEN_RECIPE';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Wooden Sword',
            description: 'A simple sword made of chestnut wood.',
            ingredients: [
                new RecipeIngredient(LogChestnut::ID, 1),
            ],
            craftingTime: 5.0,
            requirements: [
            ],
            rewards: [
                new MasteryReward(type: MasteryType::SWORD_CRAFTING, experience: 0.1),
                new ItemReward(itemId: WoodenSword::ID, quantity: 1),
            ]
        );
    }
}