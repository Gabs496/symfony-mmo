<?php

namespace App\GameElement\Core\GameObjectPrototype;

use App\GameElement\Core\GameComponent\Exception\InvalidGameComponentException;
use App\GameElement\Core\GameComponent\GameComponent;
use App\GameElement\Core\GameObject\Entity\GameObject;
use ReflectionClass;
use Symfony\Contracts\Cache\CacheInterface;

abstract class AbstractGameObjectPrototype extends GameObject implements GameObjectPrototypeInterface
{

    public function __construct(
        private readonly CacheInterface $gameObjectCache
    )
    {
        parent::__construct(self::getType(), $this->getGameComponentFromAttributes());
    }

    /**
     * @throws InvalidGameComponentException
     */
    public function make(): GameObject
    {
        return new GameObject(self::getType(), $this->getComponents());
    }

    private function getGameComponentFromAttributes(): array
    {
        return $this->gameObjectCache->get(str_replace("\\", "_", $this::class) . '_components', function () {
            $components = [];
            $reflection = new ReflectionClass($this);
            foreach ($reflection->getAttributes() as $attribute) {
                $name = $attribute->getName();
                if (is_subclass_of($name, GameComponent::class)) {

                    /** @var GameComponent $component */
                    $component = $attribute->newInstance();
                    $components[$name::getComponentName()] = $component;
                }
            }
            return $components;
        });
    }

    public static function getType(): string
    {
        $explodedClass = explode("\\", static::class);
        return array_pop($explodedClass);
    }
}