<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\Entity\Core\GameObject;
use App\GameElement\Core\GameComponent\Exception\InvalidGameComponentException;
use App\GameElement\Core\GameComponent\GameComponentInterface;
use ReflectionClass;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

abstract class AbstractGameObjectPrototype implements GameObjectPrototypeInterface
{
    private array $components = [];

    public function __construct(private readonly CacheInterface $gameObjectCache)
    {
        $this->components = $this->getGameComponentFromAttributes();
    }

    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @throws InvalidGameComponentException
     */
    public function make(): GameObject
    {
        return new GameObject($this, $this->getComponents());
    }

    private function getGameComponentFromAttributes(): array
    {
        return $this->gameObjectCache->get(str_replace("\\", "_", $this::class) . '_components', function (ItemInterface $item) {
            $components = [];
            $reflection = new ReflectionClass($this);
            foreach ($reflection->getAttributes() as $attribute) {
                if (is_subclass_of($attribute->getName(), GameComponentInterface::class)) {
                    $components[] = $attribute->newInstance();
                }
            }
            return $components;
        });

    }
}