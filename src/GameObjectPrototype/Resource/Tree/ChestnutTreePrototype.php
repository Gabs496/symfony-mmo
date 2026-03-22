<?php

namespace App\GameObjectPrototype\Resource\Tree;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Gathering\GatherRewardsInterface;
use App\GameElement\Item\Reward\ItemRuntimeCreatedReward;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObject\Mastery\Gathering\Woodcutting;
use App\GameObjectPrototype\Item\Resource\Log\ChestnutLogPrototype;
use PennyPHP\Core\AbstractGameObjectPrototype;

#[RenderComponent(
    name: 'Chestnut Tree',
    description: 'A chestnut tree.',
    iconPath: '/resource_gathering/tree_chestnut.png'
)]
#[ResourceComponent(
    gatheringDifficulty: 1.0,
    involvedMastery: Woodcutting::ID,
)]
class ChestnutTreePrototype extends AbstractGameObjectPrototype implements GatherRewardsInterface
{
    public const string ID = 'RESOURCE_LOG_TREE';

    public function getGatherRewards(): array
    {
        return [
            new ItemRuntimeCreatedReward(ChestnutLogPrototype::ID, 1),
            new MasteryReward(Woodcutting::getId(), 0.01)
        ];
    }

    public static function getType(): string
    {
        return self::ID;
    }
}