<?php

namespace App\Engine\Item;

use App\Engine\Item\Action\AbstractAvailableAction;
use App\Engine\Item\Action\Drop;
use App\Engine\Item\Action\Equip;
use App\Engine\Item\Action\Unequip;
use App\Engine\Player\PlayerItemEngine;
use App\Entity\Data\BackpackItemBag;
use App\Entity\Data\EquippedItemBag;
use App\Entity\Data\ItemInstance;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Item\Event\ItemActionPerformedEvent;
use App\GameElement\Item\ItemInstanceInterface;
use App\GameElement\ItemEquiment\Component\ItemEquipmentComponent;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ItemActionEngine
{
    public function __construct(
        protected PlayerItemEngine $playerItemEngine,
        protected EventDispatcherInterface $dispatcher,
    )
    {
    }

    public function getAvailableActions(ItemInstance $itemInstance): array
    {
        $bag = $itemInstance->getBag();

        $actions = [];
        if ($itemInstance->hasComponent(ItemEquipmentComponent::class)) {
            if ($bag instanceOf BackpackItemBag) {
                $actions[] = new Equip();
            }

            if ($bag instanceof EquippedItemBag) {
                $actions[] = new Unequip();
            }
        }

        $actions[] = new Drop();

        return $actions;
    }

    public function performItemAction(object $performer, AbstractAvailableAction $action, ItemInstanceInterface $itemInstance, array $targets): void
    {
        $event = new ItemActionPerformedEvent($performer, $action, $itemInstance, $targets);

        match ($action::class) {
            Equip::class => $this->handleEquip($event),
            Unequip::class => $this->handleUnequip($event),
            Drop::class => $this->handleDrop($event),
            default => throw new RuntimeException(sprintf('Not supported action (%s)', $action::class))
        };

        $this->dispatcher->dispatch($event);
    }

    protected function handleEquip(ItemActionPerformedEvent $event): void
    {
        if (count($event->getTargets()) !== 1) {
            throw new RuntimeException('Invalid number of targets for equip action');
        }

        $target = array_values($event->getTargets())[0];

        if ($target instanceof PlayerCharacter) {
            $itemInstance = $event->getItemInstance();
            if (!$itemInstance->hasComponent(ItemEquipmentComponent::class)) {
                throw new RuntimeException('Invalid item type for equip action');
            }
            $this->playerItemEngine->equip($itemInstance, $target);
        }
    }

    protected function handleUnequip(ItemActionPerformedEvent $event): void
    {
        if (count($event->getTargets()) !== 1) {
            throw new RuntimeException('Invalid number of targets for equip action');
        }

        $target = array_values($event->getTargets())[0];

        if ($target instanceof PlayerCharacter) {
            $itemInstance = $event->getItemInstance();
            if (!$itemInstance->hasComponent(ItemEquipmentComponent::class)) {
                throw new RuntimeException('Invalid item type for unequip action');
            }
            $this->playerItemEngine->unequip($itemInstance, $target);
        }
    }

    protected function handleDrop(ItemActionPerformedEvent $event): void
    {
        if (count($event->getTargets()) !== 1) {
            throw new RuntimeException('Invalid number of targets for drop action');
        }

        $target = array_values($event->getTargets())[0];

        if ($target instanceof PlayerCharacter) {
            $itemInstance = $event->getItemInstance();
            $this->playerItemEngine->takeItem($target, $itemInstance, $itemInstance->getQuantity());
        }
    }
}