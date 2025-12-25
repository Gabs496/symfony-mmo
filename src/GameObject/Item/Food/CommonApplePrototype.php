<?php

namespace App\GameObject\Item\Food;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Gathering\GatherableInterface;
use App\GameElement\Healing\Component\HealingComponent;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Item\Render\ItemBagRenderTemplateComponent;
use App\GameElement\Map\Render\MapRenderTemplateComponent;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObject\Mastery\Gathering\Harvesting;

#[RenderComponent(
    name: 'Apple',
    description: 'A common apple, perfect for a quick snack or to restore a small amount of health.',
    iconPath: '/items/resource_food_common_apple.png'
)]
#[ItemComponent(weight: 0.5)]
#[ResourceComponent(
    gatheringDifficulty: 0.2,
    involvedMastery: Harvesting::ID,
)]
#[HealingComponent(0.05)]

#[MapRenderTemplateComponent('Render:MapRenderTemplate',)]
#[ItemBagRenderTemplateComponent('Render:ItemBagRenderTemplate')]
class CommonApplePrototype extends AbstractGameObjectPrototype implements GatherableInterface
{
    public const string ID = 'RESOURCE_FOOD_COMMON_APPLE';

    public function getGatherRewards(): array
    {
        return [
            new MasteryReward(Harvesting::getId(), 0.01),
        ];
    }

    public function getId(): string
    {
        return self::ID;
    }
}