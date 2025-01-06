<?php

namespace App\GameObject\Gathering;

use App\GameElement\Gathering\AbstractResource;
use App\GameObject\Item\Resource\Ore\CopperOre;
use App\GameObject\Mastery\Mining;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.resource')]
readonly class OreCopper extends AbstractResource
{
    public const string ID = 'ORE_COPPER';

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Copper Ore',
            difficulty: 0.5,
            involvedMastery: new Mining(),
            rewardItemId: CopperOre::ID,
            gatheringTime: 1.5
        );
    }

}