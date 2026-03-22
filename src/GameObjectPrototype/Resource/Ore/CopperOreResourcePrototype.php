<?php

namespace App\GameObjectPrototype\Resource\Ore;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Gathering\GatherRewardsInterface;
use App\GameElement\Item\Reward\ItemRuntimeCreatedReward;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObject\Mastery\Gathering\Mining;
use App\GameObjectPrototype\Item\Resource\Ore\CopperOrePrototype;
use PennyPHP\Core\AbstractGameObjectPrototype;

#[RenderComponent(
    name: 'Coppper Ore',
    description: 'A piece of copper ore.',
    iconPath: '/resource_gathering/ore_copper.png'
)]
#[ResourceComponent(
    gatheringDifficulty: 1.5,
    involvedMastery: Mining::ID,
)]
class CopperOreResourcePrototype extends AbstractGameObjectPrototype implements GatherRewardsInterface
{
    public const string ID = 'RESOURCE_ORE_COPPER';

    public function getGatherRewards(): array
    {
        return [
            new ItemRuntimeCreatedReward(CopperOrePrototype::ID, 1),
            new MasteryReward(Mining::getId(), 0.01),
        ];
    }

    public static function getType(): string
    {
        return self::ID;
    }
}