<?php

namespace App\GameObject\Map;

use App\GameElement\Gathering\Spawn\ResourceSpawn;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObjectPrototype\Item\Food\CommonApplePrototype;
use App\GameObjectPrototype\Item\Resource\Log\ChestnutLogPrototype;
use App\GameObjectPrototype\Item\Resource\Ore\CopperOrePrototype;
use App\GameObjectPrototype\Mob\Animal\Salamander;
use App\GameObjectPrototype\Mob\Animal\Sbinsol;
use App\GameObjectPrototype\Resource\Food\CommonAppleResourcePrototype;
use App\GameObjectPrototype\Resource\Ore\CopperOreResourcePrototype;
use App\GameObjectPrototype\Resource\Tree\ChestnutTreePrototype;
use PennyPHP\Core\AbstractGameObject;
use PennyPHP\Core\InMemoryGameObjectInterface;

#[RenderComponent(name: 'BirthTown')]
#[MapComponent(
    coordinateX: 0.0,
    coordinateY: 0.0,
    spawns: [
        new ResourceSpawn(ChestnutTreePrototype::ID, 30, 0.2, 1, 5),
        new ResourceSpawn(CopperOreResourcePrototype::ID, 30, 0.333, 1, 3),
        new ResourceSpawn(CommonAppleResourcePrototype::ID, 10, 0.25, 1, 1),
        new ObjectSpawn(Salamander::ID, 10, 0.5),
        new ObjectSpawn(Sbinsol::ID, 3, 0.25),
    ]
)]
class BirthTown extends AbstractGameObject implements InMemoryGameObjectInterface
{
    public const string ID = "MapBirthTown";

    public function __construct()
    {
        parent::__construct(self::ID);
    }

    public function getId(): string
    {
        return self::ID;
    }

    public static function getType(): string
    {
        return self::ID;
    }
}