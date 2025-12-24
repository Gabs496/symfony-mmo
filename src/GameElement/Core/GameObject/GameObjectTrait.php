<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\Exception\InvalidGameComponentException;
use App\GameElement\Core\GameComponent\GameComponentInterface;

trait GameObjectTrait
{
    public function __construct(
        protected string $id,
        /** @var GameComponentInterface[] */
        protected array $components = [],
    )
    {
        foreach ($this->components as $component) {
            if (!$component instanceof GameComponentInterface) {
                throw new InvalidGameComponentException(print_r($component, true) . " is not an instance of " . GameComponentInterface::class);
            }
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    //TODO: fix this trait to use the correct type for components
    // need to fix serializing
    public function getComponents(): array
    {
        return $this->components;
    }

    public function setComponent(GameComponentInterface $component, ?string $componentId = null): self
    {
        $this->components[$componentId ?? $component::getId()] = $component;
        return $this;
    }

    public function removeComponent(string $componentId): void
    {
        unset($this->components[$componentId]);
    }

    public function hasComponent(string $componentClass): bool
    {
        return $this->getComponent($componentClass) !== null;
    }

    public function getComponent(string $componentClass): ?GameComponentInterface
    {
        if ($component = $this->components[$componentClass::getId()] ?? null) {
            return $component;
        }

        foreach ($this->components as $component) {
            if ($component::getId() === $componentClass::getId()) {
                return $component;
            }
        }

        return null;
    }

    public function clone(): GameObjectInterface
    {
        return new $this($this->getPrototype(), $this->cloneComponents());
    }

    /** @return GameComponentInterface[] */
    private function cloneComponents(): array
    {
        return array_map(function ($component) {
            return clone $component;
        }, $this->components);
    }

    public  function __toString(): string
    {
        return $this::class . '::' . $this->getId();
    }
}