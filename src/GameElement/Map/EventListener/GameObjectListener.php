<?php

namespace App\GameElement\Map\EventListener;

use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Map\Event\MapFieldChangedEvent;
use PennyPhp\ServerToClientEvent\EventDispatcher\ClientEventDispatcher;
use PennyPHP\Core\Event\GameObjectNewEvent;
use PennyPHP\Core\Event\GameObjectRemoveEvent;
use PennyPHP\Core\Event\GameObjectUpdateEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class GameObjectListener
{
    private const int DEDOUPLICATION_MILLISECONDS = 500;

    public function __construct(
        private readonly ClientEventDispatcher $clientEventDispatcher,
        private array                          $lastMapUpdate =[],
    )
    {
    }

    #[AsEventListener(GameObjectNewEvent::class)]
    #[AsEventListener(GameObjectUpdateEvent::class)]
    #[AsEventListener(GameObjectRemoveEvent::class)]
    public function updateMapField(GameObjectNewEvent|GameObjectUpdateEvent|GameObjectRemoveEvent $event): void
    {
        if ($inMapComponent = $event->getGameObject()->getComponent(InMapComponent::class)) {
            $now = microtime(true);
            if (!($lastUpdate = $this->lastMapUpdate[$inMapComponent->getMapId()] ?? false) || $lastUpdate < $now-self::DEDOUPLICATION_MILLISECONDS/1000) {
                $this->lastMapUpdate[$inMapComponent->getMapId()] = $now;
                $this->clientEventDispatcher->dispatch(new MapFieldChangedEvent($inMapComponent->getMapId()));
            }
        }
    }
}
