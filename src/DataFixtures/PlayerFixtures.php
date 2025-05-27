<?php

namespace App\DataFixtures;

use App\Entity\Data\PlayerCharacter;
use App\Entity\Security\User;
use App\GameObject\Map\BirthTown;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PlayerFixtures extends Fixture
{
    public function __construct(
        protected UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('dev@dev.org')
            ->setPassword($this->passwordHasher->hashPassword($user, 'devpassword'))
        ;

        $playerCharacter = new PlayerCharacter();
        $playerCharacter
            ->setUser($user)
            ->setName('Dev Player')
            ->setCurrentHealth(0.25)
            ->setPosition(BirthTown::ID)
        ;
        $user->addPlayerCharacter($playerCharacter);

        $manager->persist($user);
        $manager->flush();
    }
}
