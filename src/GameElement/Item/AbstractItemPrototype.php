<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameObject\AbstractGameObject;

readonly abstract class AbstractItemPrototype extends AbstractGameObject
{
    public function __construct(
        string $id,
        protected string $name,
        protected string $description = '',
        protected bool $stackable = false,
        array $components = [],
    )
    {
        parent::__construct($id, $components);
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
}