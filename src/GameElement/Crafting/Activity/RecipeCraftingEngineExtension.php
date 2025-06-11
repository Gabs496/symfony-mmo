<?php

namespace App\GameElement\Crafting\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Crafting\Event\BeforeCraftingTakeIngredientEvent;
use App\GameElement\Reward\Engine\RewardEngine;
use App\GameElement\Reward\RewardApply;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Throwable;

/** @extends ActivityEngineExtensionInterface<RecipeCraftingActivity> */
readonly class RecipeCraftingEngineExtension implements ActivityEngineExtensionInterface
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected RewardEngine $rewardEngine,
    )
    {
    }

    public static function getId(): string
    {
        return self::class;
    }

    public function supports(AbstractActivity $activity): bool
    {
        return $activity instanceof RecipeCraftingActivity;
    }

    public function getDuration(AbstractActivity $activity): float
    {
        return $activity->getRecipe()->getCraftingTime();
    }

    public function beforeStart(AbstractActivity $activity): void
    {
        $this->takeIngredient($activity);
    }

    public function onComplete(AbstractActivity $activity): void
    {
        $this->reward($activity);
    }

    public function onFinish(AbstractActivity $activity): void
    {
        return;
    }

    public function cancel(AbstractActivity $activity): void
    {
        return;
    }

    /**
     * @throws Throwable
     */
    protected function takeIngredient(RecipeCraftingActivity $activity): void
    {
        $takeIngredientEvent = new BeforeCraftingTakeIngredientEvent($activity->getSubject(), $activity->getRecipe());
        $this->eventDispatcher->dispatch($takeIngredientEvent);
        if (!$takeIngredientEvent->isProcessed()) {
            throw new RuntimeException("Crafting activity not started, ingredient not taken. Must add listener to " . BeforeCraftingTakeIngredientEvent::class . ' and be sure to execute "setProcessed()" after taking the ingredients.');
        }
    }

    protected function reward(RecipeCraftingActivity $activity): void
    {
        foreach ($activity->getRewards() as $reward) {
            $this->rewardEngine->apply(new RewardApply($reward, $activity->getSubject()));
        }
    }
}