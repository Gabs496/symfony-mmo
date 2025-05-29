<?php

namespace App\GameObject\Gathering;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\Gathering\Reward\ItemReward;
use App\GameObject\Item\Resource\Ore\CopperOrePrototype;
use App\GameObject\Mastery\Gathering\Mining;

class OreCopper extends AbstractResource
{
    public const string ID = 'ORE_COPPER';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Copper Ore',
            difficulty: 0.5,
            involvedMastery: Mining::getId(),
            rewards: [
                new ItemReward(CopperOrePrototype::ID, 1),
                new MasteryReward(Mining::getId(), 0.01),
            ],
            gatheringTime: 1.5
        );
    }

}