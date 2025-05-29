<?php

namespace App\GameObject\Gathering;

use App\Engine\Reward\MasteryReward;
use App\GameElement\Gathering\AbstractResource;
use App\GameElement\Gathering\Reward\ItemReward;
use App\GameObject\Item\Resource\Log\ChestnutLogPrototype;
use App\GameObject\Mastery\Gathering\Woodcutting;

class LogChestnut extends AbstractResource
{
    public const string ID = 'LOG_CHESTNUT';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Chestnut Log',
            difficulty: 0.5,
            involvedMastery: Woodcutting::getId(),
            rewards: [
                new ItemReward(ChestnutLogPrototype::ID, 1),
                new MasteryReward(Woodcutting::getId(), 0.01)
            ],
            gatheringTime: 1.5,
        );
    }

}