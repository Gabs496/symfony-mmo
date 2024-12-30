<?php

namespace App\GameObject\Resource;

use App\Entity\MasteryType;
use App\GameElement\Resource;
use App\GameObject\AbstractGameObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[Resource(
    id: self::ID,
    name: 'Chestnut Log',
    difficulty: 0.5,
    involvedMastery: MasteryType::WOODCUTTING,
    rewardItemId: 'LOG_CHESTNUT',
    gatheringTime: 1.5
)]
#[AutoconfigureTag('game.resource')]
readonly class LogChestnut extends AbstractGameObject
{
    public const string ID = 'LOG_CHESTNUT';
    public static function getId(): string
    {
        return self::ID;
    }
}