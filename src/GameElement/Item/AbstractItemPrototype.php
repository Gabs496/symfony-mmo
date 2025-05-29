<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;
use App\GameElement\Core\GameObject\GameObjectPrototypeInterface;
use App\GameElement\Render\Component\Render;

abstract class AbstractItemPrototype implements GameObjectPrototypeInterface
{
    use GameComponentOwnerTrait;
    public function __construct(
        protected string $id,
        string $name,
        string $description = '',
        ?string $iconPath = null,
        protected bool $stackable = false,
        protected array $components = [],
    )
    {
        $this->setComponent(Render::class, new Render(
            name: $name,
            description: $description,
            iconPath:  $iconPath ?? '/items/' . strtolower($id) . '.png',
        ));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isStackable(): bool
    {
        return $this->stackable;
    }
}