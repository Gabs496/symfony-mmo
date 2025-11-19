<?php

namespace App\GameElement\Map\Listener;

use App\Entity\Core\GameObject;
use App\Entity\Map\MapObject;
use App\Repository\Game\MapObjectRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

#[AsEntityListener(event: Events::postPersist, entity: MapObject::class)]
#[AsEntityListener(event: Events::postUpdate, entity: GameObject::class)]
#[AsEntityListener(event: Events::postRemove, entity: MapObject::class)]
readonly class MapObjectListener
{
    public function __construct(
        private HubInterface        $mercureHub,
        private MapObjectRepository $mapObjectRepository,
        private Environment         $twig,
    ) {
    }

    public function postPersist(MapObject $mapObject): void
    {
        $gameObject = $mapObject->getGameObject();
        $this->mercureHub->publish(new Update('map_objects_' . $mapObject->getMap()->getId(), $this->twig->load('map/field.stream.html.twig')->renderBlock('create', ['id' => $gameObject->getId(), 'entity' => $mapObject]), true));
    }

    public function postUpdate(GameObject $gameObject): void
    {
        /** @var MapObject $mapObject */
        $mapObject = $this->mapObjectRepository->findOneBy(['gameObject' => $gameObject]);
        if (!$mapObject) {
            return;
        }

        $this->mercureHub->publish(new Update('map_objects_' . $mapObject->getMap()->getId(), $this->twig->load('map/field.stream.html.twig')->renderBlock('update', ['id' => $gameObject->getId(), 'entity' => $mapObject]), true));
    }

    public function postRemove(MapObject $mapObject): void
    {
        $gameObject = $mapObject->getGameObject();

        $this->mercureHub->publish(new Update('map_objects_' . $mapObject->getMap()->getId(), $this->twig->load('map/field.stream.html.twig')->renderBlock('remove', ['id' => $gameObject->getId(), 'entity' => $mapObject]), true));
    }
}