<?php

namespace App\Engine\Player;

use App\Engine\PlayerCharacterManager;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Crafting\Event\BeforeCraftingTakeIngredientEvent;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class PlayerCraftingEngine
{
    public function __construct(
        private PlayerItemEngine $itemEngine,
        private PlayerCharacterRepository $playerCharacterRepository,
    ){}


    #[AsEventListener(BeforeCraftingTakeIngredientEvent::class)]
    public function takeIngredient(BeforeCraftingTakeIngredientEvent $event): void
    {
        $subject = $event->getSubject();
        if (!$subject instanceof PlayerCharacterManager) {
            return;
        }
        $player = $this->playerCharacterRepository->find($subject->getId());
        $recipe = $event->getRecipe();
        if (!$player instanceof PlayerCharacter) {
            return;
        }

        try {
            foreach ($recipe->getIngredients() as $ingredient) {
                $this->itemEngine->takeItem($player, $ingredient->getItem(), $ingredient->getQuantity());
            }
            $event->setProcessed();
        } catch (ItemQuantityNotAvailableException $e) {
            throw new UserNotificationException($player->getId(),'Recipe ingredients not availables');
        }
    }
}