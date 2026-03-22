<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PennyPHP\Core\Engine\GameObjectEngine;

class MapFixtures extends Fixture
{
    public function __construct(
        private readonly GameObjectEngine $gameObjectEngine,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
//        $birthTown = $this->gameObjectEngine->make(BirthTown::class);
//        $manager->persist($birthTown);
//        $this->addReference('map_birthTown', $birthTown);
//
//        $manager->flush();
    }
}
