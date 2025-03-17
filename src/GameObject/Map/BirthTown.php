<?php

namespace App\GameObject\Map;

use App\GameElement\MapMob\MapMobSpawn;
use App\GameElement\MapResource\MapResourceSpawn;
use App\GameObject\Gathering\LogChestnut;
use App\GameObject\Gathering\OreCopper;
use App\GameObject\Mob\Animal\Salamander;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('game.object')]
readonly class BirthTown extends AbstractBaseMap
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

    public function getSpawningResources(): array
    {
        return [
            new MapResourceSpawn(LogChestnut::ID, 100, 5, 25),
            new MapResourceSpawn(OreCopper::ID, 100, 5, 15),
        ];
    }

    public function getSpawningMobs(): array
    {
        return [
            new MapMobSpawn(Salamander::ID, 10, 5),
        ];
    }
}