<?php

namespace App\Core\GameObject;

use InvalidArgumentException;

abstract readonly class AbstractGameObjectCollection
{
    protected iterable $gameObjects;

    public function get(string $id)
    {
        foreach ($this->gameObjects as $gameObject) {
            if ($gameObject->getId() === $id) {
                return $gameObject;
            }
        }

        throw new InvalidArgumentException(sprintf('Game object with id "%s" not found in %s', $id, $this::class));
    }
}