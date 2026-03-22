<?php

namespace App\GameElement\Map\EventListener;

use App\GameElement\Map\Component\InMapComponent;
use PennyPHP\Core\Event\GameObjectRemoveEvent;
use PennyPHP\Core\Event\GameObjectUpdateEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\UX\Turbo\Broadcaster\BroadcasterInterface;

readonly class GameObjectListener
{
    public function __construct(
        private BroadcasterInterface $twigBroadcaster,
    )
    {

    }

    #[AsEventListener(GameObjectUpdateEvent::class)]
    public function onUpdate(GameObjectUpdateEvent $event): void
    {
        if ($event->getGameObject()->hasComponent(InMapComponent::class)) {
            $this->twigBroadcaster->broadcast($event->getGameObject(), 'update', [
                'template' => 'map/field.stream.html.twig',
                'topics' => 'map_field_'. $event->getGameObject()->getComponent(InMapComponent::class)->getMapId(),
            ]);
        }
    }

    #[AsEventListener(GameObjectRemoveEvent::class)]
    public function orRemove(GameObjectRemoveEvent $event): void
    {
        if ($event->getGameObject()->hasComponent(InMapComponent::class)) {
            $this->twigBroadcaster->broadcast($event->getGameObject(), 'remove', [
                'template' => 'map/field.stream.html.twig',
                'topics' => 'map_field_'. $event->getGameObject()->getComponent(InMapComponent::class)->getMapId(),
            ]);
        }
    }
}