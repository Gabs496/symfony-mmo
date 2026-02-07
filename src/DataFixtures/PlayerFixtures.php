<?php

namespace App\DataFixtures;

use App\Entity\Data\Player;
use App\Entity\Security\User;
use PennyPHP\Core\GameObject\Engine\GameObjectEngine;
use PennyPHP\Core\GameObject\Entity\GameObject;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Position\Component\PositionComponent;
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
        $user->addPlayerCharacter($playerComponent);
    }

    private function spawnInMap(GameObject $playerGameObject): void
    {
        $playerGameObject->getComponent(PositionComponent::class)
            ->setPlaceType(MapComponent::getComponentName())
            ->setPlaceId(BirthTown::ID)
            ->setPosition('field')
        ;
    }
}
