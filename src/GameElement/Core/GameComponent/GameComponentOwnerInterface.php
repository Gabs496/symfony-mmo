<?php

namespace App\GameElement\Core\GameComponent;

interface GameComponentOwnerInterface
{
    public function getComponents(): array;

    public function hasComponent(string $componentClass): bool;

    public function getComponent(string $componentClass): ?AbstractGameComponent;
}