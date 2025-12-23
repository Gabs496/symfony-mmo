<?php

namespace App\Engine\Player;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Core\GameObject\Engine\GameObjectEngine;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Crafting\AbstractItemRecipe;
use App\GameElement\Crafting\Engine\CraftingEngine;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;

readonly class PlayerCraftingEngine
{
    public function __construct(
        private PlayerItemEngine $itemEngine,
        private GameObjectEngine $gameObjectEngine,
        private CraftingEngine   $craftingEngine,
    ){}

    public function startCrafting(PlayerCharacter $playerCharacter, AbstractItemRecipe $recipe): void
    {
        self::takeIngredient($playerCharacter, $recipe);
        $this->craftingEngine->startCrafting($playerCharacter->getGameObject(), $recipe);
    }

    private function takeIngredient(PlayerCharacter $player, AbstractItemRecipe $recipe): void
    {
        try {
            foreach ($recipe->getIngredients() as $ingredient) {
                /** @var AbstractGameObjectPrototype $itemPrototype */
                $itemPrototype = $this->gameObjectEngine->getPrototype($ingredient->getItemPrototypeId());
                $this->itemEngine->takeItem($player, $itemPrototype, $ingredient->getQuantity());
            }
        } catch (ItemQuantityNotAvailableException) {
            throw new UserNotificationException($player->getId(),'Recipe ingredients not availables');
        }
    }
}