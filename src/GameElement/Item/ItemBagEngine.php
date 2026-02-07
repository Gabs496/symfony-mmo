<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameObject\Engine\GameObjectEngine;
use App\GameElement\Core\GameObject\Entity\GameObject;
use App\GameElement\Item\Component\ItemBagComponent;
use App\GameElement\Item\Component\ItemComponent;
use App\GameElement\Item\Event\ItemBagUpdate;
use App\GameElement\Item\Exception\ItemQuantityNotAvailableException;
use App\GameElement\Item\Exception\MaxBagSizeReachedException;
use App\GameElement\Position\Event\GameObjectMovingEvent;
use App\GameElement\Position\PositionEngine;
use App\GameElement\Render\Component\RenderComponent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class ItemBagEngine
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private GameObjectEngine         $gameObjectEngine,
        private PositionEngine           $positionEngine,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[AsEventListener(GameObjectMovingEvent::class)]
    public function onMoving(GameObjectMovingEvent $event): void
    {
        if (!$event->getGameObject()->hasComponent(ItemComponent::class)) {
            return;
        }

        if ($event->getPositionComponent()->getPlaceType() !== ItemBagComponent::getComponentName()) {
            return;
        }

        $itemBag = $this->entityManager->getRepository(ItemBagComponent::class)->find($event->getPositionComponent()->getPlaceId());

        if ($this->isFull($itemBag)) {
            throw new MaxBagSizeReachedException();
        }

        self::tryToMerge($itemBag,$event->getGameObject());

        $this->eventDispatcher->dispatch(new ItemBagUpdate($itemBag));
    }


    public function tryToMerge(ItemBagComponent $bag, GameObject $itemToAdd): void
    {
        foreach (self::getItems($bag) as $existingItem) {
            $existingItemComponent = $existingItem->getComponent(ItemComponent::class);
            if ($existingItem->isInstanceOf($itemToAdd) && !$existingItemComponent->isStackFull()) {
                self::merge($existingItem, $itemToAdd);
                $this->entityManager->remove($itemToAdd);
                return;
            }
        }
    }

    public function isFull(ItemBagComponent $bag): bool
    {
        return round(self::getFullness($bag), 4) === 1.0;
    }

    /** @return array<GameObject> */
    public function getItems(ItemBagComponent $bag): array
    {
        return $this->positionEngine->getContents(ItemBagComponent::getComponentName(), $bag->getId());
    }

    public function getOccupedSpace(ItemBagComponent $bag): float
    {
        return array_reduce(self::getItems($bag),
            fn($carry, GameObject $item)
            => (float)bcadd($carry, bcmul($item->getComponent(ItemComponent::class)->getWeight(), $item->getComponent(ItemComponent::class)->getQuantity(), 2), 2),
            0.0
        );
    }

    public function getFullness(ItemBagComponent $bag): float
    {
        return (float)bcdiv($this->getOccupedSpace($bag), $bag->getSize(), 2);
    }

    /** @return array<GameObject> */
    public function findAndExtract(ItemBagComponent $bag, string $type, int $quantity = 1): array
    {
        if (!$this->has($bag, $type, $quantity)) {
            $prototype = $this->gameObjectEngine->getPrototype($type);
            throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $prototype->getComponent(RenderComponent::class)->getName(), $quantity));
        }

        $items = [];
        $extractedQuantity = 0;
        foreach (self::getItems($bag) as $item) {
            if (!$item->isInstanceOf($type)) {
                continue;
            }

            $extractedItem = self::extract($item, $quantity-$extractedQuantity);
            $extractedQuantity += $extractedItem->getComponent(ItemComponent::class)->getQuantity();
            $items[] = $extractedItem;

            if ($extractedQuantity === $quantity) {
                return $items;
            }
        }

        throw new ItemQuantityNotAvailableException(sprintf('%s quantity (%s) not available', $type, $quantity));
    }

    /**
     * @throws ItemQuantityNotAvailableException
     */
    private function extract(GameObject $itemToExtract, int $maxQuantity = 0): GameObject
    {
            $itemComponent = $itemToExtract->getComponent(ItemComponent::class);
            if ($itemComponent->getQuantity() <= $maxQuantity) {
                return $itemToExtract;
            }

            $itemComponent->decreaseBy($maxQuantity);
            $newGameObject = $itemToExtract->clone();
            $extractedItem = $newGameObject->getComponent(ItemComponent::class);
            $extractedItem->setQuantity($maxQuantity);
            return $newGameObject;
    }

    public function has(ItemBagComponent $bag, string $type, int $quantity = 1): bool
    {
        return $this->getQuantity($bag, $type) >= $quantity;
    }

    public function getQuantity(ItemBagComponent $bag, string $type): int
    {
        $instances = $this->find($bag, $type);
        return array_reduce($instances, fn($carry, ItemComponent $instance)
        => $carry + $instance->getGameObject()->getComponent(ItemComponent::class)->getQuantity(), 0
        );
    }

    /** @return ItemComponent[] */
    public function find(ItemBagComponent $bag, string $type): array
    {
        $items = [];
        foreach (self::getItems($bag) as $item) {
            if ($item->isInstanceOf($type)) {
                $items[] = $item;
            }
        }
        return $items;
    }

    private function merge(GameObject $item, GameObject $toMerge): void
    {
        $item->getComponent(ItemComponent::class)->increaseBy($toMerge->getComponent(ItemComponent::class)->getQuantity());
    }
}