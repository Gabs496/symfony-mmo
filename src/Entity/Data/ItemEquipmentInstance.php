<?php

namespace App\Entity\Data;

use App\GameElement\Combat\StatCollection;
use App\GameElement\Combat\Stats\AbstractStat;
use App\GameElement\ItemEquiment\AbstractItemEquipmentInstance;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class ItemEquipmentInstance extends ItemInstance
{
    use AbstractItemEquipmentInstance;

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