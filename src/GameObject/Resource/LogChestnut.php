<?php

namespace App\GameObject\Resource;

use App\Entity\MasteryType;

#[AsResource(
    id: 'LOG_CHESTNUT',
    name: 'Chestnut Log',
    difficulty: 0.5,
    involvedMastery: MasteryType::WOODCUTTING,
    rewardItemId: 'LOG_CHESTNUT',
    gatheringTime: 1.5
)]
class LogChestnut implements ResourceInterface
{
}