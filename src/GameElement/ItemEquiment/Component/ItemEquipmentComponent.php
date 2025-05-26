<?php

namespace App\GameElement\ItemEquiment\Component;

use App\GameElement\Core\GameComponent\GameComponentInterface;

class ItemEquipmentComponent implements GameComponentInterface
{
    protected ItemStatComponent $itemStatComponent;
    protected ItemConditionComponent $itemConditionComponent;

    public function __construct(
        array $stats = [],
        float $maxCondition = 0.0,
    ) {
        $this->itemStatComponent = new ItemStatComponent($stats);
        $this->itemConditionComponent = new ItemConditionComponent($maxCondition);
    }

    public function getItemStatComponent(): ItemStatComponent
    {
        return $this->itemStatComponent;
    }

    public function setItemStatComponent(ItemStatComponent $itemStatComponent): void
    {
        $this->itemStatComponent = $itemStatComponent;
    }

    public function getItemConditionComponent(): ItemConditionComponent
    {
        return $this->itemConditionComponent;
    }

    public function setItemConditionComponent(ItemConditionComponent $itemConditionComponent): void
    {
        $this->itemConditionComponent = $itemConditionComponent;
    }
}