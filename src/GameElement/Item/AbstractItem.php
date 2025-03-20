<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameObject\AbstractGameObject;
use App\GameElement\Item\AvailableAction\AbstractAvailableAction;

readonly abstract class AbstractItem extends AbstractGameObject
{
    public function __construct(
        string $id,
        protected string $name,
        protected string $description = '',
        protected bool $stackable = false,
        protected float $weight = 100.0,
        /** @var AbstractAvailableAction[] $availableActions */
        protected array $availableActions = []
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

    public function isStackable(): bool
    {
        return $this->stackable;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getAvailableActions(): array
    {
        return $this->availableActions;
    }
}