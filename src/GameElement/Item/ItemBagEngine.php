<?php

namespace App\GameElement\Item;

use App\Engine\Math;
use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Item\Component\ItemInBagSlotComponent;
use App\GameElement\Item\Event\ItemBagUpdate;
use App\GameElement\Item\Event\ItemExtractedEvent;
use App\GameElement\Item\Exception\CannotMergeItemException;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Item\Repository\ItemInBagSlotRepository;
use App\GameElement\Render\Component\RenderComponent;
use Doctrine\ORM\EntityManagerInterface;
use PennyPHP\Core\Engine\GameObjectEngine;
use PennyPHP\Core\Entity\GameObject;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class ItemBagEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private GameObjectEngine         $gameObjectEngine,
        private EntityManagerInterface   $entityManager,
        private ItemInBagSlotRepository  $itemInBagSlotRepository,
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
            $slot = new ItemInBagSlotComponent($bag, $quantity);
            $item->getGameObject()->setComponent($slot);
            $this->entityManager->persist($slot);
        }
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new ItemBagUpdate($bag));
    }


    /**
     * @throws CannotMergeItemException
     */
    public function tryToMerge(ItemBagComponent $bag, GameObject $itemToAdd, int $quantity = 0): void
    {
        foreach ($this->itemInBagSlotRepository->findInBag($bag) as $slot) {
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
        $slots = $this->itemInBagSlotRepository->findInBag($bag);
        return array_reduce($slots, function (float $carry, ItemInBagSlotComponent $slot) {
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

        $slots = $this->itemInBagSlotRepository->findInBag($bag);
        $extractionEvents = [];
        $extractedQuantity = 0;
        foreach ($slots as $slot) {
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
    private function extract(ItemInBagSlotComponent $sourceSlot, int $maxQuantity = 0): ItemExtractedEvent
    {
            $itemComponent = $sourceSlot->getItem();
            if ($sourceSlot->getQuantity() <= $maxQuantity) {
                $this->entityManager->remove($sourceSlot);
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
            fn($carry, ItemInBagSlotComponent $slot) => $carry + $slot->getQuantity(),
            0
        );
    }

    /** @return ItemInBagSlotComponent[] */
    public function find(ItemBagComponent $bag, string $type): array
    {
        $slots = $this->itemInBagSlotRepository->findInBag($bag);
        return array_filter($slots, function (ItemInBagSlotComponent $slot) use ($type) {
            return $slot->getGameObject()->isInstanceOf($type);
        });
    }
}