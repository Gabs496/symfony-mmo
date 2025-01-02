<?php

namespace App\GameObject\Gathering;

use App\GameElement\Gathering\AbstractResource;
use App\GameElement\Mastery\MasteryType;
use App\GameObject\Item\Resource\Log\ChestnutLog;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.resource')]
readonly class LogChestnut extends AbstractResource
{
    public const string ID = 'LOG_CHESTNUT';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Chestnut Log',
            difficulty: 0.5,
            involvedMastery: MasteryType::WOODCUTTING,
            rewardItemId: ChestnutLog::ID,
            gatheringTime: 1.5,
        );
    }

}