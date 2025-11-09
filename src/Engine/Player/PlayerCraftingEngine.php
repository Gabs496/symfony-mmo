<?php

namespace App\Engine\Player;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Core\GameObject\Engine\GameObjectEngine;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\Activity\RecipeCraftingActivity;
use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;

readonly class PlayerCraftingEngine
{
    public function __construct(
        private PlayerItemEngine $itemEngine,
        private GameObjectEngine $gameObjectEngine,
        private ActivityEngine   $activityEngine,
    ){}

    public function startCrafting(PlayerCharacter $playerCharacter, AbstractRecipe $recipe): void
    {
        self::takeIngredient($playerCharacter, $recipe);
        $this->activityEngine->run(new RecipeCraftingActivity($playerCharacter, $recipe));
    }

    private function takeIngredient(PlayerCharacter $player, AbstractRecipe $recipe): void
    {
        try {
            foreach ($recipe->getIngredients() as $ingredient) {
                /** @var AbstractItemPrototype $itemPrototype */
                $itemPrototype = $this->gameObjectEngine->getPrototype($ingredient->getItemPrototypeId());
                $this->itemEngine->takeItem($player, $itemPrototype, $ingredient->getQuantity());
            }
        } catch (ItemQuantityNotAvailableException) {
            throw new UserNotificationException($player->getId(),'Recipe ingredients not availables');
        }
    }
}