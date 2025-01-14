<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameObject\AbstractGameObject;

readonly abstract class AbstractItem extends AbstractGameObject implements ItemInterface
{
    public function __construct(
        string $id,
        protected string $name,
        protected string $description = '',
        protected bool $equippable = false,
        protected bool $consumable = false,
        protected bool $stackable = false,
        protected float $maxCondition = 0.0,
        protected float $weight = 100.0,
    )
    {
        parent::__construct($id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isEquippable(): bool
    {
        return $this->equippable;
    }

    public function isConsumable(): bool
    {
        return $this->consumable;
    }

    public function isStackable(): bool
    {
        return $this->stackable;
    }

    public function getMaxCondition(): float
    {
        return $this->maxCondition;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }
}