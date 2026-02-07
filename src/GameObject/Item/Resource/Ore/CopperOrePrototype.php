<?php

namespace App\GameObject\Item\Resource\Ore;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Gathering\GatherableInterface;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObject\Mastery\Gathering\Mining;

#[RenderComponent(
    name: 'Coppper Ore',
    description: 'A piece of copper ore.',
    iconPath: '/items/resource_ore_copper.png'
)]
#[ItemComponent(weight: 0.2)]
#[ResourceComponent(
    gatheringDifficulty: 1.5,
    involvedMastery: Mining::ID,
)]

class CopperOrePrototype extends AbstractGameObjectPrototype implements GatherableInterface
{
    public const string ID = 'RESOURCE_ORE_COPPER';

    public function getGatherRewards(): array
    {
        return [
            new MasteryReward(Mining::getId(), 0.01),
        ];
    }

    public static function getType(): string
    {
        return self::ID;
    }
}