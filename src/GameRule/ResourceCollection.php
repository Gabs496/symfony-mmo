<?php

namespace App\GameRule;

use App\GameObject\Resource\AsResource;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class ResourceCollection
{
    private array $resources;

    /**
     * @throws ReflectionException
     */
    public function __construct(
        #[AutowireIterator('game.resource')]
        iterable $resources
    ) {
        $resourceAttributes = [];
        foreach ($resources as $resource) {
            $reflection = new ReflectionClass($resource);
            /** @var AsResource $resourceAttribute */
            foreach ($reflection->getAttributes(AsResource::class) as $resourceAttribute) {
                $resourceAttributes[] = $resourceAttribute->newInstance();
            }
        }
        $this->resources = $resourceAttributes;
    }

    /**
     * @throws ReflectionException
     */
    public function getResource(string $id): AsResource
    {
        foreach ($this->resources as $resource) {
            if ($resource->getId() === $id) {
                return $resource;
            }
        }

        throw new InvalidArgumentException(sprintf('Resource with id "%s" not found', $id));
    }
}