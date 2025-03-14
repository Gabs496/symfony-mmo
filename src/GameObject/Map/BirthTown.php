<?php

namespace App\GameObject\Map;

use App\GameElement\Map\AbstractMap;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
readonly class BirthTown extends AbstractMap
{
    public const string ID = "MAP_BIRT_TOWN";

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Birt Town',
            coordinateX: 0.0,
            coordinateY: 0.0
        );
    }
}