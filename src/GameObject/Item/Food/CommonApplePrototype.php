<?php

namespace App\GameObject\Item\Food;

use App\Engine\Reward\MasteryReward;
use App\Entity\Core\GameObject;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameElement\Healing\Component\HealingComponent;
use App\GameObject\Item\AbstractItemFoodPrototype;
use App\GameObject\Mastery\Gathering\Mining;

class CommonApplePrototype extends AbstractItemFoodPrototype
{
    public const string ID = 'RESOURCE_FOOD_COMMON_APPLE';
    public function make(
        array $components = [new HealingComponent(0.05)],
        string $name = 'Apple',
        string $description = 'A common apple, perfect for a quick snack or to restore a small amount of health.',
        float $weight = 0.05,
    ): GameObject
    {
        return parent::make(
            components: $components,
            name: $name,
            description: $description,
            weight: $weight,
        );
    }

    public function asGatherableComponents(): array
    {
        return [
            new GatheringComponent(
                difficulty: 0.5,
                involvedMastery: Mining::getId(),
                gatheringTime: 1.5,
                rewards: [
                    new MasteryReward(Mining::getId(), 0.01),
                ]
            )
        ];
    }

    public static function getId(): string
    {
        return self::ID;
    }
}