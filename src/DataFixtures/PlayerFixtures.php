<?php

namespace App\DataFixtures;

use App\Entity\Data\PlayerCharacter;
use App\Entity\Security\User;
use App\GameElement\Core\GameObject\Engine\GameObjectEngine;
use App\GameObject\Map\BirthTown;
use App\GameObject\PlayerCharacter\BasePlayer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PlayerFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly GameObjectEngine            $gameObjectEngine,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('dev@dev.org')
            ->setPassword($this->passwordHasher->hashPassword($user, 'devpassword'))
        ;

        $playerGameObject = $this->gameObjectEngine->getPrototype(BasePlayer::getId())->make();

        $playerCharacter = new PlayerCharacter($playerGameObject);
        $playerCharacter
            ->setUser($user)
            ->setName('Dev Player')
            ->setMap(new BirthTown())
        ;
        $user->addPlayerCharacter($playerCharacter);

        $manager->persist($user);
        $manager->flush();
    }
}
