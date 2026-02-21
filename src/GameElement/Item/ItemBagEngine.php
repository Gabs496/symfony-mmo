<?php

namespace App\GameElement\Item;

use App\Engine\Math;
use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Item\Component\ItemBagSlot;
use App\GameElement\Item\Event\ItemBagUpdate;
use App\GameElement\Item\Event\ItemExtractedEvent;
use App\GameElement\Item\Exception\CannotMergeItemException;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Render\Component\RenderComponent;
use Doctrine\ORM\EntityManagerInterface;
use PennyPHP\Core\Entity\GameObject;
use PennyPHP\Core\GameObject\Engine\GameObjectEngine;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class ItemBagEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private GameObjectEngine         $gameObjectEngine,
        private EntityManagerInterface   $entityManager,
    )
    {
    }

    public function put(ItemBagComponent $bag, ItemComponent $item, int $quantity = 1): void
    {
        if ($this->isFull($bag)) {
            throw new MaxBagSizeReachedException();
        }

        try {
            self::tryToMerge($bag,$item->getGameObject(), $quantity);
        } catch (CannotMergeItemException) {
            $slot = new ItemBagSlot($bag, $item, $quantity);
            $bag->addSlot($slot);
        }
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new ItemBagUpdate($bag));
    }


    /**
     * @throws CannotMergeItemException
     */
    public function tryToMerge(ItemBagComponent $bag, GameObject $itemToAdd, int $quantity = 0): void
    {
        foreach ($bag->getSlots() as $slot) {
            $existingItem = $slot->getItem()->getGameObject();

            if ($existingItem->isInstanceOf($itemToAdd) && !$slot->isFull()) {
                $slot->increaseBy($quantity);
                $this->entityManager->remove($itemToAdd);
                return;
            }
        }

        throw new CannotMergeItemException();
    }

    public function isFull(ItemBagComponent $bag): bool
    {
        return round(self::getFullness($bag), 4) === 1.0;
    }

    public function getOccupedSpace(ItemBagComponent $bag): float
    {
        return $bag->getSlots()->reduce(function (float $carry, ItemBagSlot $slot) {
            return Math::round($carry + $slot->getItem()->getWeight() * $slot->getQuantity());
        }, 0.0);
    }

    public function getFullness(ItemBagComponent $bag): float
    {
        return (float)bcdiv($this->getOccupedSpace($bag), $bag->getMaxSize(), 2);
    }

    /** @return array<ItemExtractedEvent> */
    public function findAndExtract(ItemBagComponent $bag, string $type, int $quantity = 1): array
    {
        if (!$this->has($bag, $type, $quantity)) {
            $prototype = $this->gameObjectEngine->getPrototype($type);
            throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $prototype->getComponent(RenderComponent::class)->getName(), $quantity));
        }

        $extractionEvents = [];
        $extractedQuantity = 0;
        foreach ($bag->getSlots() as $slot) {
            $item = $slot->getItem()->getGameObject();
            if (!$item->isInstanceOf($type)) {
                continue;
            }

            $extractionEvent = self::extract($slot, $quantity-$extractedQuantity);
            $extractedQuantity += $extractionEvent->getQuantity();
            $extractionEvents[] = $extractionEvent;

            if ($extractedQuantity === $quantity) {
                return $extractionEvents;
            }
        }

        throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $type, $quantity));
    }

    /**
     * @throws ItemQuantityNotAvailableException
     */
    private function extract(ItemBagSlot $sourceSlot, int $maxQuantity = 0): ItemExtractedEvent
    {
            $itemComponent = $sourceSlot->getItem();
            if ($sourceSlot->getQuantity() <= $maxQuantity) {
                $sourceSlot->getBag()->removeSlot($sourceSlot);
                return new ItemExtractedEvent($itemComponent, $sourceSlot->getQuantity());
            }

            $sourceSlot->decreaseBy($maxQuantity);
            $newGameObject = $itemComponent->getGameObject()->clone();
            return new ItemExtractedEvent($newGameObject->getComponent(ItemComponent::class), $maxQuantity);
    }

    public function has(ItemBagComponent $bag, string $type, int $quantity = 1): bool
    {
        return $this->getQuantity($bag, $type) >= $quantity;
    }

    public function getQuantity(ItemBagComponent $bag, string $type): int
    {
        $slots = $this->find($bag, $type);
        return array_reduce($slots,
            fn($carry, ItemBagSlot $slot) => $carry + $slot->getQuantity(),
            0
        );
    }

    /** @return ItemBagSlot[] */
    public function find(ItemBagComponent $bag, string $type): array
    {
        $slots = [];
        foreach ($bag->getSlots() as $slot) {
            if ($slot->getItem()->getGameObject()->isInstanceOf($type)) {
                $slots[] = $slot;
            }
        }
        return $slots;
    }
}