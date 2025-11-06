<?php

namespace App\GameObject\Item\Resource\Ore;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameObject\Item\AbstractItemResourcePrototype;
use App\GameObject\Mastery\Gathering\Mining;

class CopperOrePrototype extends AbstractItemResourcePrototype
{
    public const string ID = 'RESOURCE_ORE_COPPER';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Coppper Ore',
            description: 'A piece of copper ore.',
            weight: 0.1,
            components: [
                new GatheringComponent(
                    difficulty: 0.5,
                    involvedMastery: Mining::getId(),
                    gatheringTime: 1.5,
                    rewards: [
                        new MasteryReward(Mining::getId(), 0.01),
                    ]
                )
            ]
        );
    }
}