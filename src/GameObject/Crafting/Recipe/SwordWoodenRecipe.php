<?php

namespace App\GameObject\Crafting\Recipe;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\RecipeIngredient;
use App\GameElement\Gathering\Reward\ItemReward;
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
                new RecipeIngredient(new ChestnutLogPrototype(), 10),
            ],
            craftingTime: 5.0,
            requirements: [
            ],
            rewards: [
                new MasteryReward(type: new SwordCrafting(), experience: 0.1),
                new ItemReward(item: new WoodenSwordPrototype(), quantity: 1),
            ]
        );
    }
}