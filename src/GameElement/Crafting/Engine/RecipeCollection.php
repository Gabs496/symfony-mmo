<?php

namespace App\GameElement\Crafting\Engine;

use App\GameElement\Core\GameObject\AbstractGameObjectCollection;
use App\GameElement\Core\GameObject\GameObjectCollection;
use App\GameElement\Crafting\AbstractRecipe;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/** @template-extends AbstractGameObjectCollection<AbstractRecipe> */
#[AutoconfigureTag('game.object_collection')]
#[GameObjectCollection(AbstractRecipe::class)]
readonly class RecipeCollection extends AbstractGameObjectCollection
{
    public function __construct(
        #[AutowireIterator('game.recipe')]
        protected iterable $gameObjects
    )
    {
    }
}