<?php

namespace App\GameEngine\Map;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class MapEngineCollection
{
    public function __construct(
        /** @var AbstractMapEngine[] $mapEngines */
        #[AutowireIterator('game.engine.map')]
        protected iterable $mapEngines,
    ) {
    }

    public function get(string $id): AbstractMapEngine
    {
        foreach ($this->mapEngines as $mapEngine) {
            if ($mapEngine->getMap()->getId() === $id) {
                return $mapEngine;
            }
        }

        throw new InvalidArgumentException("Map engine with id $id not found");
    }
}