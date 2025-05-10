<?php

namespace App\GameElement\Core\GameComponent;

trait GameComponentOwnerTrait
{
    public function getComponents(): array
    {
        return $this->components;
    }

    public function addComponent(AbstractGameComponent $component): void
    {
        $this->components[] = $component;
    }

    public function removeComponent(AbstractGameComponent $component): void
    {
        foreach ($this->components as $key => $existingComponent) {
            if ($existingComponent === $component) {
                unset($this->components[$key]);
                return;
            }
        }
    }

    public function hasComponent(string $componentClass): bool
    {
        return $this->getComponent($componentClass) !== null;
    }

    /**
     * @template T of AbstractGameComponent
     * @param class-string<T> $componentClass
     * @return T|null $componentClass
     */
    public function getComponent(string $componentClass): ?AbstractGameComponent
    {
        foreach ($this->components as $component) {
            /** @var AbstractGameComponent $component */
            if ($component instanceof $componentClass) {
                return $component;
            }
        }

        return null;
    }
}