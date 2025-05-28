<?php

namespace App\GameElement\ItemEquiment\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

class ItemEquipmentComponent implements GameComponentInterface
{
    protected ItemStat $itemStatComponent;
    protected ItemCondition $itemConditionComponent;

    public function __construct(
        array $stats = [],
        float $maxCondition = 0.0,
    ) {
        $this->itemStatComponent = new ItemStat($stats);
        $this->itemConditionComponent = new ItemCondition($maxCondition);
    }

    public function getItemStatComponent(): ItemStat
    {
        return $this->itemStatComponent;
    }

    public function setItemStatComponent(ItemStat $itemStatComponent): void
    {
        $this->itemStatComponent = $itemStatComponent;
    }

    public function getItemConditionComponent(): ItemCondition
    {
        return $this->itemConditionComponent;
    }

    public function setItemConditionComponent(ItemCondition $itemConditionComponent): void
    {
        $this->itemConditionComponent = $itemConditionComponent;
    }
}