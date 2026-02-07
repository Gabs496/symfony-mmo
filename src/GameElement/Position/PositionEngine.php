<?php

namespace App\GameElement\Position;

use App\GameElement\Core\GameObject\Entity\GameObject;
use App\GameElement\Position\Component\PositionComponent;
use App\GameElement\Position\Event\GameObjectMovingEvent;
use App\Repository\Game\GameObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class PositionEngine
{
    public function __construct(
        private GameObjectRepository $gameObjectRepository,
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $entityManager,
    )
    {

    }

    public function move(GameObject|PositionComponent $object, string $placeType, GameObject|string $place, string $position = null): void
    {
        $positionComponent = ($object instanceof PositionComponent ? $object : $object->getComponent(PositionComponent::class));
        $placeId = is_string($place) ? $place : "game_object_" . $place->getId();

        $positionComponent
            ->setPlaceType($placeType)
            ->setPlaceId($placeId)
            ->setPosition($position)
        ;
        $this->eventDispatcher->dispatch(new GameObjectMovingEvent($positionComponent->getGameObject(), $positionComponent));
        $this->gameObjectRepository->save($object);
    }

    /** @return array<PositionComponent> */
    public function getContents(string $placeType, string $placeId): array
    {
        return $this->entityManager->getRepository(PositionComponent::class)->findBy([
            'placeType' => $placeType,
            'placeId' => $placeId,
        ]);
    }
}