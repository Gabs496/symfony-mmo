<?php

namespace App\GameObject\Item\Food;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameElement\Healing\Component\HealingComponent;
use App\GameObject\Item\AbstractItemFoodPrototype;
use App\GameObject\Mastery\Gathering\Mining;

class CommonApplePrototype extends AbstractItemFoodPrototype
{
    public const string ID = 'RESOURCE_FOOD_COMMON_APPLE';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Apple',
            description: 'A common apple, perfect for a quick snack or to restore a small amount of health.',
            weight: 0.05,
            components: [
                new HealingComponent(0.05),
                new GatheringComponent(
                    difficulty: 0.5,
                    involvedMastery: Mining::getId(),
                    gatheringTime: 1.5,
                    rewards: [
                        new MasteryReward(Mining::getId(), 0.01),
                    ]
                )
            ],
        );
    }
}