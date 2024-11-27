<?php

namespace App\Entity\Abstract;

use App\Entity\Data\ItemInstanceBagCollection;

abstract class Character
{
    protected ?string $name = null;

    protected ?ItemInstanceBagCollection $itemBagCollection = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getItemBagCollection(): ?ItemInstanceBagCollection
    {
        return $this->itemBagCollection;
    }

    public function setItemBagCollection(?ItemInstanceBagCollection $itemBagCollection): void
    {
        $this->itemBagCollection = $itemBagCollection;
    }
}