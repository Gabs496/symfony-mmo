<?php

namespace App\GameElement\Core\GameComponent;

trait GameComponentOwnerTrait
{
    //TODO: fix this trait to use the correct type for components
    // need to fix serializing
    public function getComponents(): array
    {
        return $this->components;
    }

    public function setComponent(string $componentId, GameComponentInterface $component): void
    {
        $this->components[$componentId] = $component;
    }

    public function removeComponent(string $componentId): void
    {
        unset($this->components[$componentId]);
    }

    public function hasComponent(string $componentClass): bool
    {
        return $this->getComponent($componentClass) !== null;
    }

    /**
     * @template T of GameComponentInterface
     * @param class-string<T> $componentClass
     * @return T|null $componentClass
     */
    public function getComponent(string $componentClass): ?GameComponentInterface
    {
        foreach ($this->components as $component) {
            if ($component instanceof $componentClass) {
                return $component;
            }
        }

        return null;
    }
}