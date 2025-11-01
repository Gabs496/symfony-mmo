<?php

namespace App\GameElement\Map\Event\Listener;

use App\Entity\Game\GameObject;
use App\Entity\Game\MapObject;
use App\GameElement\Core\GameObject\Event\GameObjectUpdateEvent;
use App\Repository\Game\MapObjectRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

readonly class GameObjectListener
{
    public function __construct(
        private HubInterface        $mercureHub,
        private MapObjectRepository $mapObjectRepository,
        private Environment         $twig,
    ) {
    }

    #[AsEventListener(event: GameObjectUpdateEvent::class)]
    public function onGameObjectUpdate(GameObjectUpdateEvent $event): void
    {
        $gameObject = $event->getGameObject();
        /** @var MapObject $mapObject */
        $mapObject = $this->mapObjectRepository->findOneBy(['gameObject' => $gameObject]);
        if (!$mapObject) {
            return;
        }

        $this->mercureHub->publish(new Update('map_objects_' . $mapObject->getMapId(), $this->twig->load('streams/map_objects_list.stream.html.twig')->renderBlock('update', ['id' => $mapObject->getId(), 'entity' => $mapObject]), true));
    }
}