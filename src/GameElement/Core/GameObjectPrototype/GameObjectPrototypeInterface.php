<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameObject\GameObjectInterface;

interface GameObjectPrototypeInterface
{
    public static function getId(): string;

    /** @return GameComponentInterface[] */
    public function getComponents(): array;

    /**
     * @template T of GameComponentInterface
     * @param class-string<T> $componentClass
     * @return T|null
     */
    public function getComponent(string $componentClass): ?GameComponentInterface;

    public function make(): GameObjectInterface;
}