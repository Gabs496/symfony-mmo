<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;
use App\GameElement\Core\GameObject\GameObjectPrototypeInterface;

abstract class AbstractItemPrototype implements GameObjectPrototypeInterface
{
    use GameComponentOwnerTrait;
    public function __construct(
        protected string $id,
        string $name,
        string $description = '',
        ?string $iconPath = null,
        protected array $components = [],
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}