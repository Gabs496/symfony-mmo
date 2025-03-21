<?php

namespace App\GameObject\Crafting\Recipe;

use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\RecipeIngredient;
use App\GameElement\Item\Reward\ItemReward;
use App\GameElement\Mastery\MasteryReward;
use App\GameObject\Item\Equipment\Sword\WoodenSword;
use App\GameObject\Item\Resource\Log\ChestnutLog;
use App\GameObject\Mastery\Crafting\SwordCrafting;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
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
                new RecipeIngredient(new ChestnutLog(), 10),
            ],
            craftingTime: 5.0,
            requirements: [
            ],
            rewards: [
                new MasteryReward(type: new SwordCrafting(), experience: 0.1),
                new ItemReward(item: new WoodenSword(), quantity: 1),
            ]
        );
    }
}