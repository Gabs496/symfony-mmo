<?php

namespace App\GameElement\Crafting\Activity;

use App\Engine\Player\PlayerEngine;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Notification\Exception\UserNotificationException;
use App\GameElement\Reward\RewardApply;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/** @extends ActivityEngineExtensionInterface<PlayerCharacter,RecipeCraftingEngineExtension> */
readonly class RecipeCraftingEngineExtension implements ActivityEngineExtensionInterface
{
    public function __construct(
        //TODO: questa proprietà non è di questo dominio. Rimuoverla
        private PlayerEngine $playerEngine,
        private MessageBusInterface $messageBus,
    )
    {
    }

    public static function getId(): string
    {
        return self::class;
    }

    /**
     * @psalm-param  PlayerCharacter $subject
     * @psalm-param   AbstractRecipe $directObject
     * @throws Throwable
     */
    #[AsEventListener(BeforeActivityStartEvent::class)]
    public  function beforeActivityStart(BeforeActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof RecipeCraftingActivity) {
            return;
        }

        $this->takeIngredient($event->getSubject(), $activity->getRecipe());
        $activity->setDuration($activity->getRecipe()->getCraftingTime());
    }

    #[AsEventListener(ActivityEndEvent::class)]
    public function onActivityEnd(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof RecipeCraftingActivity) {
            return;
        }

        foreach ($activity->getRewards() as $reward) {
            $this->messageBus->dispatch(new RewardApply($reward, $event->getSubject()));
        }
    }

    /**
     * @psalm-param  PlayerCharacter $subject
     * @psalm-param   AbstractRecipe $directObject
     * @throws UserNotificationException
     */
    private function takeIngredient(object $subject, object $directObject): void
    {
        try {
            foreach ($directObject->getIngredients() as $ingredient) {
                $this->playerEngine->takeItem($subject, $ingredient->getItem(), $ingredient->getQuantity());
            }
        } catch (ItemQuantityNotAvailableException $e) {
            throw new UserNotificationException($subject->getId(),'Recipe ingredients not availables');
        }
    }
}