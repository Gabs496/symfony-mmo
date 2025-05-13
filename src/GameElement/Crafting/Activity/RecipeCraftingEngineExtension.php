<?php

namespace App\GameElement\Crafting\Activity;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\Event\BeforeCraftingTakeIngredientEvent;
use App\GameElement\Reward\RewardApply;
use Psr\EventDispatcher\EventDispatcherInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/** @extends ActivityEngineExtensionInterface<PlayerCharacter,RecipeCraftingEngineExtension> */
readonly class RecipeCraftingEngineExtension implements ActivityEngineExtensionInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private EventDispatcherInterface $eventDispatcher,
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

        $takeIngredientEvent = new BeforeCraftingTakeIngredientEvent($event->getSubject(), $activity->getRecipe());
        $this->eventDispatcher->dispatch($takeIngredientEvent);
        if (!$takeIngredientEvent->isProcessed()) {
            throw new RuntimeException("Crafting activity not started, ingredient not taken. Must add listener to " . BeforeCraftingTakeIngredientEvent::class . ' and be sure to execute "setProcessed()" after taking the ingredients.');
        }
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
}