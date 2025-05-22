<?php

namespace App\GameElement\Crafting\Activity;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Activity\Event\ActivityEndEvent;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use App\GameElement\Crafting\AbstractRecipe;
use App\GameElement\Crafting\Event\BeforeCraftingTakeIngredientEvent;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Throwable;

/** @extends ActivityEngineExtensionInterface<PlayerCharacter,RecipeCraftingEngineExtension> */
readonly class RecipeCraftingEngineExtension implements ActivityEngineExtensionInterface, EventSubscriberInterface
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected RewardEngine $rewardEngine,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeActivityStartEvent::class => [
                ['dispatchTakeIngredient', 0],
            ],
            ActivityEndEvent::class => [
                ['reward', 0]
            ]
        ];
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
    public  function dispatchTakeIngredient(BeforeActivityStartEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof RecipeCraftingActivity) {
            return;
        }

        $takeIngredientEvent = new BeforeCraftingTakeIngredientEvent($event->getActivity()->getSubject(), $activity->getRecipe());
        $this->eventDispatcher->dispatch($takeIngredientEvent);
        if (!$takeIngredientEvent->isProcessed()) {
            throw new RuntimeException("Crafting activity not started, ingredient not taken. Must add listener to " . BeforeCraftingTakeIngredientEvent::class . ' and be sure to execute "setProcessed()" after taking the ingredients.');
        }
    }

    public function reward(ActivityEndEvent $event): void
    {
        $activity = $event->getActivity();
        if (!$activity instanceof RecipeCraftingActivity) {
            return;
        }

        foreach ($activity->getRewards() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $event->getActivity()->getSubject()));
        }
    }
}