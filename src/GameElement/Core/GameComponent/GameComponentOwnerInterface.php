<?php

namespace App\GameElement\Core\GameComponent;

interface GameComponentOwnerInterface
{
    /** @return GameComponentInterface[] */
    public function getComponents(): array;

    public function setComponent(GameComponentInterface $component, ?string $componentId = null): void;

    public function removeComponent(string $componentId): void;

    public function hasComponent(string $componentClass): bool;

    /**
     * @template T of GameComponentInterface
     * @param class-string<T> $componentClass
     * @return T|null $componentClass
     */
    public function getComponent(string $componentClass): ?GameComponentInterface;
}