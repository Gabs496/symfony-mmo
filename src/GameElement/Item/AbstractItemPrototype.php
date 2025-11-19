<?php

namespace App\GameElement\Item;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameComponent\GameComponentOwnerTrait;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use App\GameElement\Render\Component\RenderComponent;

abstract class AbstractItemPrototype implements GameObjectPrototypeInterface
{
    use GameComponentOwnerTrait;

    /** @var GameComponentInterface[] */
    protected array $components = [];

    public function __construct(
        protected string $id,
        string $name,
        string $description = '',
        ?string $iconPath = null,
        array $components = [],
    )
    {
        $this->components = array_merge(
            [
                RenderComponent::getId() => new RenderComponent(
                    template: 'Render:ItemRenderTemplate',
                    name: $name,
                    description: $description,
                    iconPath: '/items/' . strtolower($id) . '.png'
                ),
            ]
            , $components);
    }

    public function getId(): string
    {
        return $this->id;
    }
}