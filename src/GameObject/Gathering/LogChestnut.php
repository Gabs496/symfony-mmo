<?php

namespace App\GameObject\Gathering;

use App\GameElement\Gathering\AbstractResource;
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
            involvedMastery: new Woodcutting(),
            rewardItem: new ChestnutLogPrototype(),
            gatheringTime: 1.5,
        );
    }

}