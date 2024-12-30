<?php

namespace App\GameObject\Resource;

use App\Entity\MasteryType;
use App\GameElement\Resource;
use App\GameObject\AbstractGameObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[Resource(
    id: self::ID,
    name: 'Copper Ore',
    difficulty: 0.5,
    involvedMastery: MasteryType::MINING,
    rewardItemId: 'ORE_COPPER',
    gatheringTime: 1.5
)]
#[AutoconfigureTag('game.resource')]
readonly class OreCopper extends AbstractGameObject
{
    public const string ID = 'ORE_COPPER';
    public static function getId(): string
    {
        return self::ID;
    }
}