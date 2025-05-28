<?php

namespace App\Engine\Player;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Crafting\Event\BeforeCraftingTakeIngredientEvent;
use App\GameElement\Item\AbstractItemPrototype;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerCraftingEngine implements EventSubscriberInterface
{
    public function __construct(
        private PlayerItemEngine $itemEngine,
        private GameObjectEngine $gameObjectEngine,
    ){}

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeCraftingTakeIngredientEvent::class => [
                ['takeIngredient', 0],
            ],
        ];
    }

    public function takeIngredient(BeforeCraftingTakeIngredientEvent $event): void
    {
        $player = $event->getSubject();
        if (!$player instanceof PlayerCharacter) {
            return;
        }

        $recipe = $event->getRecipe();
        try {
            foreach ($recipe->getIngredients() as $ingredient) {
                /** @var AbstractItemPrototype $itemPrototype */
                $itemPrototype = $this->gameObjectEngine->get($ingredient->getItemPrototypeId());
                $this->itemEngine->takeItem($player, $itemPrototype, $ingredient->getQuantity());
            }
            $event->setProcessed();
        } catch (ItemQuantityNotAvailableException $e) {
            throw new UserNotificationException($player->getId(),'Recipe ingredients not availables');
        }
    }
}