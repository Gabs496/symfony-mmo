<?php

namespace App\GameElement\Equipment;

use PennyPHP\Core\GameObject\Entity\GameObject;
use App\GameElement\Equipment\Component\EquipmentComponent;
use App\GameElement\Equipment\Component\EquipmentSetComponent;
use App\GameElement\Equipment\Event\EquipEvent;
use App\GameElement\Equipment\Event\UnequipEvent;
use App\GameElement\Position\PositionEngine;
use App\Repository\Game\GameObjectRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class EquipmentEngine
{
    public function __construct(
        private PositionEngine $positionEngine,
        private GameObjectRepository $gameObjectRepository,
        private EventDispatcherInterface $eventDispatcher,
    )
    {

    }

    public function equip(GameObject $equipment, GameObject $to, string $slot): void
    {
        if (!$equipment->hasComponent(EquipmentComponent::class)) {
            //TODO: throw exception
            return;
        }

        if (!($equipmentSet = $to->getComponent(EquipmentSetComponent::class))) {
            //TODO: throw exception
            return;
        }

        if (!$equipmentSet->hasSlot($slot)) {
            //TODO: throw exception
            return;
        }

        self::unequip($to, $slot);
        $this->positionEngine->move($equipment, $to, 'equipment_' . $slot);
        $this->eventDispatcher->dispatch(new EquipEvent($equipment, $to, $slot));
        $this->gameObjectRepository->save($equipment);
    }


    public function unequip(GameObject $from ,string $slot): void
    {
        $this->eventDispatcher->dispatch(new UnequipEvent(self::getEquipment($from, $slot), $from, $slot));
    }

    public function getEquipment(GameObject $gameObject, string $slot): ?GameObject
    {
        if (!($place = $gameObject->getComponent(PlaceComponent::class))) {
            //TODO: throw exception
            return null;
        }

        return $place->getContentByPosition('equipment_' . $slot);
    }
}