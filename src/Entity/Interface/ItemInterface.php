<?php

namespace App\Entity\Abstract;

interface Item
{
    private bool $equippable = false;
    private bool $consumable = false;
    private bool $stackable = false;
    private float $maxDurability = 0.0;
    public function __construct(
        private readonly string $name,
        private readonly string $description,
        private readonly float  $weight,
        private readonly float  $minExperience,
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getMinExperience(): float
    {
        return $this->minExperience;
    }

    public function isEquippable(): bool
    {
        return $this->equippable;
    }

    public function setEquippable(bool $equippable): void
    {
        $this->equippable = $equippable;
    }

    public function isConsumable(): bool
    {
        return $this->consumable;
    }

    public function setConsumable(bool $consumable): void
    {
        $this->consumable = $consumable;
    }

    public function isStackable(): bool
    {
        return $this->stackable;
    }

    public function setStackable(bool $stackable): void
    {
        $this->stackable = $stackable;
    }

    public function getMaxDurability(): float
    {
        return $this->maxDurability;
    }

    public function setMaxDurability(float $maxDurability): void
    {
        $this->maxDurability = $maxDurability;
    }
}