<?php

namespace App\GameObject\Item\Resource\Log;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Gathering\Component\ResourceComponent;
use App\GameElement\Gathering\GatherableInterface;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObject\Mastery\Gathering\Woodcutting;

#[RenderComponent(
    name: 'Chestnut Log',
    description: 'A log from a chestnut tree.',
    iconPath: '/items/resource_log_chestnut.png'
)]
#[ItemComponent(weight: 0.1)]
#[ResourceComponent(
    gatheringDifficulty: 1.0,
    involvedMastery: Woodcutting::ID,
)]

class ChestnutLogPrototype extends AbstractGameObjectPrototype implements GatherableInterface
{
    public const string ID = 'RESOURCE_LOG_CHESTNUT';

    public function getGatherRewards(): array
    {
        return [
            new MasteryReward(Woodcutting::getId(), 0.01)
        ];
    }

    public static function getType(): string
    {
        return self::ID;
    }
}