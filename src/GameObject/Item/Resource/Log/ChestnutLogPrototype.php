<?php

namespace App\GameObject\Item\Resource\Log;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\Component\GatheringComponent;
use App\GameObject\Item\AbstractItemResourcePrototype;
use App\GameObject\Mastery\Gathering\Woodcutting;

class ChestnutLogPrototype extends AbstractItemResourcePrototype
{
    public const string ID = 'RESOURCE_LOG_CHESTNUT';
    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Chestnut Log',
            description: 'A log from a chestnut tree.',
            weight: 0.1,
            components: [
                new GatheringComponent(
                    difficulty: 0.5,
                    involvedMastery: Woodcutting::getId(),
                    gatheringTime: 1.5,
                    rewards: [
                        new MasteryReward(Woodcutting::getId(), 0.01)
                    ],
                )
            ]
        );
    }
}