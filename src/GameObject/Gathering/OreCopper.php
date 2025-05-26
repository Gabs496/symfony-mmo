<?php

namespace App\GameObject\Gathering;

use App\GameElement\Gathering\AbstractResource;
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
            rewardItem: new CopperOrePrototype(),
            gatheringTime: 1.5
        );
    }

}