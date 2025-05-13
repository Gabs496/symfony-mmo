<?php

namespace App\Engine\Player\Activity;

use App\Engine\Player\PlayerItemEngine;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Crafting\Event\BeforeCraftingTakeIngredientEvent;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class PlayerCraftingEngine
{
    public function __construct(
        private PlayerItemEngine $itemEngine,
    ){}


    #[AsEventListener(BeforeCraftingTakeIngredientEvent::class)]
    public function takeIngredient(BeforeCraftingTakeIngredientEvent $event): void
    {
        $subject = $event->getSubject();
        $recipe = $event->getRecipe();
        if (!$subject instanceof PlayerCharacter) {
            return;
        }

        try {
            foreach ($recipe->getIngredients() as $ingredient) {
                $this->itemEngine->takeItem($subject, $ingredient->getItem(), $ingredient->getQuantity());
            }
            $event->setProcessed();
        } catch (ItemQuantityNotAvailableException $e) {
            throw new UserNotificationException($subject->getId(),'Recipe ingredients not availables');
        }
    }
}