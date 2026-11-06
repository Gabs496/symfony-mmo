<?php

namespace App\GameElement\Item\EventListener;

use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\Component\ItemInBagSlotComponent;
use App\GameElement\Item\ItemBagEngine;
use App\Stream\ItemBagStream;
use App\Stream\Streamer;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use PennyPHP\Core\Engine\GameObjectEngine;
use PennyPHP\Core\Event\GameObjectUpdateEvent;
use PennyPHP\Core\GameObjectInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Throwable;

/**
 * Sends to the owner of a bag a Turbo Stream with the new rendering of each item that changed in it, plus the
 * new fullness of the bag. Changes are collected during the Doctrine flush and published once on postFlush.
 *
 * - ItemInBagSlotComponent persisted / moved into a bag -> append
 * - ItemInBagSlotComponent removed / moved out of a bag -> remove
 * - any other component change of an item in a bag      -> update
 */
#[AsEntityListener(event: Events::postPersist, method: 'onSlotPersist', entity: ItemInBagSlotComponent::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'onSlotUpdate', entity: ItemInBagSlotComponent::class)]
#[AsEntityListener(event: Events::postRemove, method: 'onSlotRemove', entity: ItemInBagSlotComponent::class)]
#[AsDoctrineListener(event: Events::postFlush)]
class GameObjectListener
{
    /** @var array<string, array{bagId: string, item: GameObjectInterface, action: string}> */
    private array $pending = [];

    public function __construct(
        private readonly Streamer         $streamer,
        private readonly GameObjectEngine $gameObjectEngine,
        private readonly ItemBagEngine    $itemBagEngine,
    )
    {
    }

    public function onSlotPersist(ItemInBagSlotComponent $slot): void
    {
        $this->queue($slot->getBagId(), $slot->getGameObject(), ItemBagStream::APPEND);
    }

    public function onSlotUpdate(ItemInBagSlotComponent $slot, PreUpdateEventArgs $args): void
    {
        if ($args->hasChangedField('bagId')) {
            $this->queue($args->getOldValue('bagId'), $slot->getGameObject(), ItemBagStream::REMOVE);
            $this->queue($slot->getBagId(), $slot->getGameObject(), ItemBagStream::APPEND);
        }
    }

    public function onSlotRemove(ItemInBagSlotComponent $slot): void
    {
        $this->queue($slot->getBagId(), $slot->getGameObject(), ItemBagStream::REMOVE);
    }

    #[AsEventListener(GameObjectUpdateEvent::class)]
    public function onGameObjectUpdate(GameObjectUpdateEvent $event): void
    {
        $slot = $event->getGameObject()->getComponent(ItemInBagSlotComponent::class);
        if ($slot instanceof ItemInBagSlotComponent) {
            $this->queue($slot->getBagId(), $event->getGameObject(), ItemBagStream::UPDATE);
        }
    }

    public function postFlush(): void
    {
        $pending = $this->pending;
        $this->pending = [];

        $bagIds = [];
        foreach ($pending as $item) {
            $this->streamer->send(new ItemBagStream($item['bagId'], $item['item'], $item['action']));
            $bagIds[$item['bagId']] = true;
        }

        foreach (array_keys($bagIds) as $bagId) {
            $this->sendFullness($bagId);
        }
    }

    private function sendFullness(string $bagId): void
    {
        try {
            $bag = $this->gameObjectEngine->get($bagId)->getComponent(ItemBagComponent::class);
        } catch (Throwable) {
            return; // the bag owner no longer exists
        }
        if ($bag instanceof ItemBagComponent) {
            $this->streamer->send(new ItemBagStream($bagId, $bag, ItemBagStream::FULLNESS, $this->itemBagEngine->getFullness($bag)));
        }
    }

    private function queue(string $bagId, GameObjectInterface $item, string $action): void
    {
        $key = $bagId . '|' . $item->getId();

        // "update" never overrides an append/remove already queued in the same flush:
        // an append already renders the latest state, and an item being removed must not be re-rendered.
        if ($action === ItemBagStream::UPDATE && isset($this->pending[$key])) {
            return;
        }

        $this->pending[$key] = ['bagId' => $bagId, 'item' => $item, 'action' => $action];
    }
}
