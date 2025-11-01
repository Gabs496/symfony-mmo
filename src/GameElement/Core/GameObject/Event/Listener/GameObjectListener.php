<?php

namespace App\GameElement\Core\GameObject\Event\Listener;

use App\Entity\Game\GameObject;
use App\GameElement\Core\GameObject\Event\GameObjectUpdateEvent;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsEntityListener(event: Events::postUpdate, entity: GameObject::class)]
readonly class GameObjectListener
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }
    public function postUpdate(GameObject $gameObject): void
    {
        $this->eventDispatcher->dispatch(new GameObjectUpdateEvent($gameObject));
    }
}