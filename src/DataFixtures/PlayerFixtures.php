<?php

namespace App\DataFixtures;

use App\Entity\Data\Player;
use App\Entity\Security\User;
use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Render\Component\RenderComponent;
use App\GameObject\Map\BirthTown;
use App\GameObjectPrototype\PlayerCharacter\BasePlayer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PennyPHP\Core\Engine\GameObjectEngine;
use PennyPHP\Core\Entity\GameObject;
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
        $playerGameObject = $this->gameObjectEngine->make(BasePlayer::class);

        $user = self::createCredentials();
        $manager->persist($user);

        self::initPlayer($playerGameObject, $user);
        self::spawnInMap($playerGameObject);

        $manager->persist($playerGameObject);
        $manager->flush();
    }

    private function createCredentials(): User
    {
        $user = new User();
        $user
            ->setEmail('dev@dev.org')
            ->setPassword($this->passwordHasher->hashPassword($user, 'devpassword'))
        ;

        return $user;
    }

    private function initPlayer(GameObject $playerGameObject, User $user): void
    {
        $playerComponent = $playerGameObject->getComponent(Player::class);
        $playerComponent
            ->setUser($user)
            ->setName('Dev Player')
        ;
        $playerGameObject->setComponent(new RenderComponent('Dev Player'));
        $user->addPlayerCharacter($playerComponent);
    }

    private function spawnInMap(GameObject $playerGameObject): void
    {
        /** @var GameObject $birthtown */
        $playerGameObject->setComponent(new InMapComponent(BirthTown::ID, "field"));
    }
}
