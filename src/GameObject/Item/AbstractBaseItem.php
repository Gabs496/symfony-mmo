<?php

namespace App\GameObject\Item;

use App\Entity\Data\ItemInstance;
use App\GameElement\Item\AbstractItem;
use App\GameElement\Item\ItemInstanceInterface;
use App\GameElement\ItemWeight\ItemWithWeightInterface;
use App\GameElement\ItemWeight\ItemWithWeightTrait;

readonly abstract class AbstractBaseItem extends AbstractItem implements ItemWithWeightInterface
{
    use ItemWithWeightTrait;

    public function __construct(
        string $id,
        string $name,
        string $description,
        bool $stackable,
        float $weight,
    )
    {
        parent::__construct(
            id: $id,
            name: $name,
            description: $description,
            stackable: $stackable,
        );
        $this->weight = $weight;
    }

    /** @return ItemInstance */
    public abstract function createInstance(): ItemInstanceInterface;
}