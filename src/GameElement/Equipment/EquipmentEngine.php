<?php

namespace App\GameElement\Equipment;

use App\GameElement\Equipment\Component\EquipmentComponent;
use App\GameElement\Equipment\Component\EquipmentSetComponent;
use App\GameElement\Equipment\Component\EquippedComponent;
use App\GameElement\Equipment\Event\EquipEvent;
use App\GameElement\Equipment\Event\UnequipEvent;
use App\GameElement\Equipment\Exception\IncompatibleEquipmentTargetSlotException;
use App\GameElement\Equipment\Repository\EquippedRepository;
use PennyPHP\Core\GameObjectInterface;
use PennyPHP\Core\Repository\GameObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class EquipmentEngine
{
    public function __construct(
        private GameObjectRepository     $gameObjectRepository,
        private EventDispatcherInterface $eventDispatcher,
        private EquippedRepository $equippedRepository,
    )
    {

    }

    /**
     * @throws IncompatibleEquipmentTargetSlotException
     */
    public function equip(EquipmentComponent $equipment, EquipmentSetComponent $equipmentSet, string $slot): void
    {
        if (!$equipmentSet->hasSlot($slot)) {
            throw new IncompatibleEquipmentTargetSlotException($slot, $equipmentSet->getSlots());
        }
        if ($equipment->getTargetSlot() !== $slot) {
            throw new IncompatibleEquipmentTargetSlotException($slot, [$equipment->getTargetSlot()]);
        }

        self::unequip($equipmentSet->getGameObject(), $slot);
        $equipment->getGameObject()->setComponent(new EquippedComponent($equipmentSet->getGameObject(), $slot));
        $this->eventDispatcher->dispatch(new EquipEvent($equipment->getGameObject(), $equipmentSet->getGameObject(), $slot));
        $this->gameObjectRepository->save($equipment);
    }


    public function unequip(GameObjectInterface $from ,string $slot): void
    {
        $equipment = self::getEquipment($from, $slot);
        $equipment->removeComponent(EquippedComponent::class);
        $this->eventDispatcher->dispatch(new UnequipEvent($equipment, $from, $slot));
    }

    public function getEquipment(GameObjectInterface $gameObject, string $slot): ?GameObjectInterface
    {

        return $this->equippedRepository->findEquipped($gameObject, $slot)?->getGameObject();
    }
}