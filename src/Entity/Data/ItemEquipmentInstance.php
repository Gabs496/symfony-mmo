<?php

namespace App\Entity\Data;

use App\GameElement\Combat\StatCollection;
use App\GameElement\Combat\Stats\AbstractStat;
use App\GameElement\ItemEquiment\AbstractItemEquipment;
use App\GameElement\ItemEquiment\ItemEquipmentInstanceInterface;
use Doctrine\ORM\Mapping\Entity;

/**
 * @property AbstractItemEquipment $item
 */
#[Entity]
class ItemEquipmentInstance extends ItemInstance implements ItemEquipmentInstanceInterface
{
    public function getCombatStatModifiers(): array
    {
        $statCollection = new StatCollection($this->item->getCombatStatModifiers());
        foreach ($this->getProperties() as $property) {
            if ($property instanceof AbstractStat) {
                $statCollection->increase($property::class, $property->getValue());
            }
        }
        return $statCollection->getStats();
    }
}