<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\Exception\InvalidGameComponentException;
use App\GameElement\Core\GameComponent\GameComponent;

trait GameObjectTrait
{
    /** @var GameComponent[] */
    protected array $components;

    /**
     * @throws InvalidGameComponentException
     */
    public function __construct(
        protected string $id,
        array $components = [],
    )
    {
        foreach ($components as $component) {
            if (!$component instanceof GameComponent) {
                throw new InvalidGameComponentException(print_r($component, true) . " is not an instance of " . GameComponent::class);
            }
        }

        /** @var GameComponent[] $components */
        $this->components = [];
        foreach ($components as $component) {
            /** @var GameComponent $component */
            $this->components[$component::getComponentName()] = $component;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    /** @return GameComponent[] */
    public function getComponents(): array
    {
        return $this->components;
    }

    public function setComponent(GameComponent $component, ?string $componentId = null): self
    {
        $this->components[$componentId ?? $component::getComponentName()] = $component;
        return $this;
    }

    /**
     * @template T of GameComponent
     * @param class-string<T> $componentClass
     */
    public function removeComponent(string $componentClass): void
    {
        unset($this->components[$componentClass::getComponentName()]);
    }

    /**
     * @template T of GameComponent
     * @param class-string<T> $componentClass
     */
    public function hasComponent(string $componentClass): bool
    {
        return $this->getComponent($componentClass) !== null;
    }

    /**
     * @template T of GameComponent
     * @param class-string<T> $componentClass
     * @return T|null
     */
    public function getComponent(string $componentClass): ?GameComponent
    {
        if ($component = $this->components[$componentClass::getComponentName()] ?? null) {
            return $component;
        }

        foreach ($this->components as $component) {
            if ($component::getComponentName() === $componentClass::getComponentName()) {
                return $component;
            }
        }

        return null;
    }

    public  function __toString(): string
    {
        return $this::class . '::' . $this->getId();
    }
}