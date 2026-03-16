<?php

namespace App\GameObject\Crafting\Recipe;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Crafting\AbstractItemRecipe;
use App\GameElement\Crafting\RecipeIngredient;
use App\GameObject\Mastery\Crafting\SwordCrafting;
use App\GameObjectPrototype\Item\Equipment\Sword\WoodenSwordPrototype;
use App\GameObjectPrototype\Item\Resource\Log\ChestnutLogPrototype;

class SwordWoodenRecipe extends AbstractItemRecipe
{
    public const string ID = 'SWORD_WOODEN_RECIPE';

    public function __construct(
        WoodenSwordPrototype $woodenSwordPrototype,
        ChestnutLogPrototype $chestnutLogPrototype,
    )
    {
        parent::__construct(
            id: self::ID,
            name: 'Wooden Sword',
            description: 'A simple sword made of chestnut wood.',
            item: $woodenSwordPrototype,
            ingredients: [
                new RecipeIngredient($chestnutLogPrototype, 10),
            ],
            craftingTime: 5.0,
            requirements: [
            ],
            rewards: [
                new MasteryReward(masteryId: SwordCrafting::getId(), experience: 0.01),
            ]
        );
    }
}