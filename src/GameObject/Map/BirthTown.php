<?php

namespace App\GameObject\Map;

use App\GameElement\Core\GameObjectPrototype\AbstractGameObjectPrototype;
use App\GameElement\Gathering\Spawn\ResourceSpawn;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObject\Item\Food\CommonApplePrototype;
use App\GameObject\Item\Resource\Log\ChestnutLogPrototype;
use App\GameObject\Item\Resource\Ore\CopperOrePrototype;
use App\GameObject\Mob\Animal\Salamander;
use App\GameObject\Mob\Animal\Sbinsol;

#[RenderComponent(name: 'BirthTown')]
#[MapComponent(
    coordinateX: 0.0,
    coordinateY: 0.0,
    spawns: [
        new ResourceSpawn(ChestnutLogPrototype::ID, 30, 0.2, 1, 5),
        new ResourceSpawn(CopperOrePrototype::ID, 30, 0.333, 1, 3),
        new ResourceSpawn(CommonApplePrototype::ID, 10, 0.25, 1, 1),
        new ObjectSpawn(Salamander::ID, 10, 0.5),
        new ObjectSpawn(Sbinsol::ID, 3, 0.25),
    ]
)]
class BirthTown extends AbstractGameObjectPrototype
{
    public const string ID = "MapBirthTown";

    public function getId(): string
    {
        return self::ID;
    }

    public static function getType(): string
    {
        return self::ID;
    }
}