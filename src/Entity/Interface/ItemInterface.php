<?php

namespace App\Entity\Interface;

interface ItemInterface
{


    public function getName(): string;

    public function getDescription(): string;

    public function getWeight(): float;

    public function getMinExperienceRequired(): float;

    public function isEquippable(): bool;

    public function isConsumable(): bool;

    public function isStackable(): bool;

    public function getMaxDurability(): float;

    public function getType(): ItemTypeInterface;
}