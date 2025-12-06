<?php

namespace App\GameObject\Crafting\Recipe;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Core\GameObject\Engine\GameObjectEngine;
use App\GameElement\Crafting\AbstractItemRecipe;
use App\GameElement\Crafting\RecipeIngredient;
use App\GameObject\Item\Equipment\Sword\WoodenSwordPrototype;
use App\GameObject\Item\Resource\Log\ChestnutLogPrototype;
use App\GameObject\Mastery\Crafting\SwordCrafting;

class SwordWoodenRecipe extends AbstractItemRecipe
{
    public const string ID = 'SWORD_WOODEN_RECIPE';

    public function __construct(GameObjectEngine $gameObjectEngine)
    {
        parent::__construct(
            id: self::ID,
            name: 'Wooden Sword',
            description: 'A simple sword made of chestnut wood.',
            item: $gameObjectEngine->getPrototype(WoodenSwordPrototype::getId())->make(),
            ingredients: [
                new RecipeIngredient(ChestnutLogPrototype::ID, 10),
            ],
            craftingTime: 5.0,
            requirements: [
            ],
            rewards: [
                new MasteryReward(masteryId: SwordCrafting::getId(), experience: 0.01),
            ]
        );
    }

    public static function getId(): string
    {
        return self::ID;
    }
}