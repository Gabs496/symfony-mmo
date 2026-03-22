<?php

namespace App\GameObjectPrototype\Resource\Food;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Gathering\GatherRewardsInterface;
use App\GameElement\Item\Reward\ItemRuntimeCreatedReward;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObject\Mastery\Gathering\Harvesting;
use App\GameObjectPrototype\Item\Food\CommonApplePrototype;
use PennyPHP\Core\AbstractGameObjectPrototype;

#[RenderComponent(
    name: 'Apple',
    description: 'A common apple, perfect for a quick snack or to restore a small amount of health.',
    iconPath: '/resource_gathering/food_common_apple.png'
)]
#[ResourceComponent(
    gatheringDifficulty: 0.2,
    involvedMastery: Harvesting::ID,
)]
class CommonAppleResourcePrototype extends AbstractGameObjectPrototype implements GatherRewardsInterface
{
    public const string ID = 'RESOURCE_FOOD_COMMON_APPLE';

    public function getGatherRewards(): array
    {
        return [
            new ItemRuntimeCreatedReward(CommonApplePrototype::ID, 1),
            new MasteryReward(Harvesting::getId(), 0.01),
        ];
    }

    public static function getType(): string
    {
        return self::ID;
    }
}