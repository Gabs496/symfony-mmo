<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\GameElement\Core\GameComponent\GameComponentInterface;

abstract class AbstractGameObjectPrototype implements GameObjectPrototypeInterface
{
    use GameObjectPrototypeTrait;

    /** @var GameComponentInterface[] */
    private array $components;

    public function __construct(
        protected string $id,
        /** @param GameComponentInterface[] $components */
        array $components = [],
    )
    {
        $this->components = [];
        foreach ($components as $component) {
            $this->components[$component->getId()] = $component;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }
}