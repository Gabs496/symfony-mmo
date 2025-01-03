<?php

namespace App\Core\GameObject;

use InvalidArgumentException;

/**
 * @template T
 */
abstract readonly class AbstractGameObjectCollection
{
    /** @var T[] */
    protected iterable $gameObjects;

    /** @return T */
    public function get(string $id)
    {
        foreach ($this->gameObjects as $gameObject) {
            if ($gameObject->getId() === $id) {
                return $gameObject;
            }
        }

        throw new InvalidArgumentException(sprintf('Game object with id "%s" not found in %s', $id, $this::class));
    }

    /** @return T[] */
    public function all(): iterable
    {
        return $this->gameObjects;
    }
}