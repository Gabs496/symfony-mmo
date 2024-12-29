<?php

namespace App\GameObject\Resource;

use App\Entity\MasteryType;

#[AsResource(
    id: 'ORE_COPPER',
    name: 'Copper Ore',
    difficulty: 0.5,
    involvedMastery: MasteryType::MINING,
    rewardItemId: 'ORE_COPPER',
    gatheringTime: 1.5
)]
class OreCopper implements ResourceInterface
{
}