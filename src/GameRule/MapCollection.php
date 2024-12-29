<?php

namespace App\GameRule;

use App\GameObject\Resource\AsResource;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class MapCollection
{
    public function __construct(
        #[AutowireIterator('game.map')]
        private iterable $maps
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function getResource(string $id): AsResource
    {
        foreach ($this->maps as $map) {
            if ($map->getId() === $id) {
                return $map;
            }
        }

        throw new InvalidArgumentException(sprintf('Map with id "%s" not found', $id));
    }
}