<?php

namespace App\GameObject\Map;

use App\GameElement\Map;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[Map(
    id: self::ID,
    name: 'Birt Town',
    coordinateX: 0.0,
    coordinateY: 0.0
)]
#[AutoconfigureTag('game.map')]
readonly class BirtTown extends AbstractMapObject
{
    public const string ID = 'BIRT_TOWN';

    public static function getId(): string
    {
        return self::ID;
    }
}