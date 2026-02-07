<?php

namespace App\GameElement\Core\GameObject\Doctrine\Listener;

use App\GameElement\Core\GameObject\Entity\GameObject;
use App\GameElement\Core\GameObject\Event\GameObjectNewEvent;
use App\GameElement\Core\GameObject\Event\GameObjectRemoveEvent;
use App\GameElement\Core\GameObject\Event\GameObjectUpdateEvent;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsEntityListener(event: Events::postPersist, entity: GameObject::class)]
#[AsEntityListener(event: Events::postUpdate, entity: GameObject::class)]
#[AsEntityListener(event: Events::postRemove, entity: GameObject::class)]
readonly class GameObjectListener
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public function postPersist(GameObject $gameObject): void
    {
        $this->eventDispatcher->dispatch(new GameObjectNewEvent($gameObject));
    }

    public function postUpdate(GameObject $gameObject): void
    {
        $this->eventDispatcher->dispatch(new GameObjectUpdateEvent($gameObject));
    }

    public function postRemove(GameObject $gameObject): void
    {
        $this->eventDispatcher->dispatch(new GameObjectRemoveEvent($gameObject));
    }
}