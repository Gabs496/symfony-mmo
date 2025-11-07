<?php

namespace App\GameElement\Crafting\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Crafting\Engine\CraftingEngine;

/** @extends ActivityEngineExtensionInterface<RecipeCraftingActivity> */
readonly class RecipeCraftingEngineExtension implements ActivityEngineExtensionInterface
{
    public function __construct(
        private CraftingEngine $engine,
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
    }

    public function onComplete(AbstractActivity $activity): void
    {
        $this->engine->reward($activity);
    }

    public function onFinish(AbstractActivity $activity): void
    {
    }

    public function cancel(AbstractActivity $activity): void
    {
    }
}