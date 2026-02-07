<?php

namespace App\DataFixtures;

use PennyPHP\Core\GameObject\Engine\GameObjectEngine;
use App\GameObject\Map\BirthTown;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MapFixtures extends Fixture
{
    public function __construct(
        private readonly GameObjectEngine $gameObjectEngine,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $birthTown = $this->gameObjectEngine->make(BirthTown::class);
        $manager->persist($birthTown);
        $this->addReference('map_birthTown', $birthTown);

        $manager->flush();
    }
}
