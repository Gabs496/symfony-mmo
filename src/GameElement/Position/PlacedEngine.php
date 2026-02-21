<?php

namespace App\GameElement\Position;

use App\GameElement\Position\Component\PlacedComponent;
use Doctrine\ORM\EntityManagerInterface;
use PennyPHP\Core\Entity\GameObject;
use PennyPHP\Core\GameObject\Repository\GameObjectRepository;

/** @deprecated */
readonly class PlacedEngine
{
    public function __construct(
        private GameObjectRepository $gameObjectRepository,
        private EntityManagerInterface $entityManager,
    )
    {

    }

    public function move(GameObject|PlacedComponent $object, string $placeType, GameObject|string $place): void
    {
        $positionComponent = ($object instanceof PlacedComponent ? $object : $object->getComponent(PlacedComponent::class));
        $placeId = is_string($place) ? $place : $place->getId();

        $positionComponent
            ->setPlaceType($placeType)
            ->setPlaceId($placeId)
        ;
        $this->gameObjectRepository->save($object);
    }

    /** @return array<PlacedComponent> */
    public function getPlaceds(string $placeType, string $placeId): array
    {
        return $this->entityManager->getRepository(PlacedComponent::class)->findBy([
            'placeType' => $placeType,
            'placeId' => $placeId,
        ]);
    }
}