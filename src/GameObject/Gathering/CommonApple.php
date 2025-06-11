<?php

namespace App\GameObject\Gathering;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\GameObject\AbstractResource;
use App\GameElement\Gathering\Reward\ItemReward;
use App\GameObject\Item\Resource\Log\ChestnutLogPrototype;
use App\GameObject\Mastery\Gathering\Harvesting;
use App\GameObject\Mastery\Gathering\Woodcutting;

class FoodCommonApple extends AbstractResource
{
    public const string ID = 'FOOD_COMMON_APPLE';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Apple',
            difficulty: 0.0,
            involvedMastery: Harvesting::getId(),
            rewards: [
                new ItemReward(ChestnutLogPrototype::ID, 1),
                new MasteryReward(Woodcutting::getId(), 0.01)
            ],
            gatheringTime: 1.5,
        );
    }

}