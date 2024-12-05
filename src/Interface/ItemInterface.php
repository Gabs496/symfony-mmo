<?php

namespace App\Interface;

interface ItemInterface
{


    public function getName(): string;

    public function getDescription(): string;

    public function getWeight(): float;

    public function getAdvisedExperience(): float;

    public function isEquippable(): bool;

    public function isConsumable(): bool;

    public function isStackable(): bool;

    public function getMaxCondition(): float;

    public function getType(): ItemTypeInterface;
}