<?php

namespace App\Engine\Player;

use App\GameElement\Crafting\Event\BeforeCraftingTakeIngredientEvent;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerCraftingEngine implements EventSubscriberInterface
{
    public function __construct(
        private PlayerItemEngine $itemEngine,
        private PlayerCharacterRepository $playerCharacterRepository,
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
        $token = $event->getSubject();
        if (!$token instanceof PlayerToken) {
            return;
        }

        $player = $this->playerCharacterRepository->find($token->getId());
        $recipe = $event->getRecipe();
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