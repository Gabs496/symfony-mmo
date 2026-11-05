<?php

namespace App\GameElement\Item\EventListener;

use App\GameElement\Item\Component\ItemInBagSlotComponent;
use App\GameElement\Item\Event\ItemBagChangedEvent;
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
        private array                          $lastBagUpdate =[],
    )
    {
    }

    #[AsEventListener(GameObjectNewEvent::class)]
    #[AsEventListener(GameObjectUpdateEvent::class)]
    #[AsEventListener(GameObjectRemoveEvent::class)]
    public function updateBagSlot(GameObjectNewEvent|GameObjectUpdateEvent|GameObjectRemoveEvent $event): void
    {
        if ($bagSlotComponent = $event->getGameObject()->getComponent(ItemInBagSlotComponent::class)) {
            $now = microtime(true);
            if (!($lastUpdate = $this->lastBagUpdate[$bagSlotComponent->getBagId()] ?? false) || $lastUpdate < $now-self::DEDOUPLICATION_MILLISECONDS/1000) {
                $this->lastBagUpdate[$bagSlotComponent->getBagId()] = $now;
                $this->clientEventDispatcher->dispatch(new ItemBagChangedEvent($bagSlotComponent->getBagId()));
            }
        }
    }
}
