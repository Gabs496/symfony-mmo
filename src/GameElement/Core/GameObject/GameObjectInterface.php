<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use Stringable;

interface GameObjectInterface extends Stringable
{
    public function getId(): string;

    public function clone(): GameObjectInterface;

    /** @return GameComponentInterface[] */
    public function getComponents(): array;

    public function setComponent(GameComponentInterface $component, ?string $componentId = null);

    /**
     * @template T of GameComponentInterface
     * @param class-string<T> $componentClass
     */
    public function removeComponent(string $componentClass): void;

    public function hasComponent(string $componentClass): bool;

    /**
     * @template T of GameComponentInterface
     * @param class-string<T> $componentClass
     * @return T|null
     */
    public function getComponent(string $componentClass): ?GameComponentInterface;

    public function __toString(): string;
}