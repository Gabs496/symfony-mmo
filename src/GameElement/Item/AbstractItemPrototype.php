<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameComponent\GameComponentOwnerInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;
use App\GameElement\Core\GameObject\GameObjectInterface;

abstract class AbstractItemPrototype implements GameObjectInterface, GameComponentOwnerInterface
{
    use GameComponentOwnerTrait;
    public function __construct(
        protected string $id,
        protected string $name,
        protected string $description = '',
        protected bool $stackable = false,
        protected array $components = [],
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
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