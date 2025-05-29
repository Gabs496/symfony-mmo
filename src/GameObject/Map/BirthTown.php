<?php

namespace App\GameObject\Map;

use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use App\GameElement\Map\Component\Spawn\Spawn;
use App\GameObject\Gathering\LogChestnut;
use App\GameObject\Gathering\OreCopper;
use App\GameObject\Mob\Animal\Salamander;
use App\GameObject\Mob\Animal\Sbinsol;

class BirthTown extends AbstractBaseMap
{
    public const string ID = "MAP_BIRT_TOWN";

    public function __construct()
    {
        parent::__construct(
            id: self::ID,
            name: 'Birt Town',
            coordinateX: 0.0,
            coordinateY: 0.0,
            components: [
                new Spawn([
                    new ObjectSpawn(LogChestnut::ID, 100, 0.2),
                    new ObjectSpawn(OreCopper::ID, 100, 0.333),
                    new ObjectSpawn(Salamander::ID, 10, 0.5),
                    new ObjectSpawn(Sbinsol::ID, 3, 0.25),
                ])
            ]
        );
    }
}