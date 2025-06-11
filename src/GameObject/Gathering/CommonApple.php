<?php

namespace App\GameObject\Gathering;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\GameObject\AbstractResource;
use App\GameElement\Gathering\Reward\ItemReward;
use App\GameObject\Item\Food\CommonApplePrototype;
use App\GameObject\Mastery\Gathering\Harvesting;

class CommonApple extends AbstractResource
{
    public const string ID = 'COMMON_APPLE';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Apple',
            difficulty: 0.0,
            involvedMastery: Harvesting::getId(),
            rewards: [
                new ItemReward(CommonApplePrototype::ID, 1),
                new MasteryReward(Harvesting::getId(), 0.01)
            ],
            gatheringTime: 0.5,
        );
    }

}