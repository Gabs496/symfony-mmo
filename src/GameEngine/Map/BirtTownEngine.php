<?php

namespace App\GameEngine\Map;

use App\GameElement\Map;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[Map(
    id: self::ID,
    name: 'Birt Town',
    coordinateX: 0.0,
    coordinateY: 0.0
)]
#[AutoconfigureTag('game.engine.map')]
readonly class BirtTownEngine extends AbstractMapEngine
{
    public const string ID = 'BIRT_TOWN';

}