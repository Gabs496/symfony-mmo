<?php

namespace App\GameElement\Core\GameObject\Entity;

use App\GameElement\Core\GameComponent\Exception\InvalidGameComponentException;
use App\GameElement\Core\GameComponent\GameComponent;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use App\Repository\Game\GameObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: GameObjectRepository::class)]
#[ORM\Table(name: "core_game_object")]
class GameObject implements GameObjectInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'guid')]
    private string $id;

    /** @var array<string, GameComponent> */
    protected array $components = [];

    #[ORM\Column(name: 'type', type: 'string')]
    private string $prototype;

    /**
     * @param GameComponent[] $components
     * @throws InvalidGameComponentException
     */
    public function __construct(GameObjectPrototypeInterface|string $prototype, array $components)
    {
        $this->id = Uuid::v7();
        if ($prototype instanceof GameObjectPrototypeInterface) {
            $prototype = $prototype->getType();
        }
        $this->prototype = $prototype;

        foreach ($components as $component) {
            if (!$component instanceof GameComponent) {
                throw new InvalidGameComponentException(print_r($component, true) . " is not an instance of " . GameComponent::class);
            }
        }

        foreach ($components as $component) {
            $component->setGameObject($this);
        }

        $fastAccessComponents = [];
        foreach ($components as $component) {
            $fastAccessComponents[$component::class] = $component;
        }
        $this->components = $fastAccessComponents;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPrototype(): string
    {
        return $this->prototype;
    }

    /**
     * @template T of GameComponent
     * @param class-string<T> $componentClass
     * @return T|null
     */
    public function getComponent(string $componentClass): ?GameComponent
    {
        if ($component = $this->components[$componentClass] ?? null) {
            return $component;
        }

        foreach ($this->components as $component) {
            if ($component::getComponentName() === $componentClass) {
                return $component;
            }
        }

        return null;
    }

    public function isInstanceOf(GameObject|GameObjectPrototypeInterface|string $object): bool
    {
        if (is_subclass_of($object, GameObjectPrototypeInterface::class)) {
            $type = $object::getType();
        } elseif (is_string($object)) {
            $type = $object;
        } else {
            $type = $object->getPrototype();
        }

        return $this->getPrototype() === $type;
    }

    /** @return array<string, GameComponent> */
    public function getComponents(): array
    {
        return $this->components;
    }

    public function setComponent(GameComponent $component): self
    {
        $this->components[$component::class] = $component;
        return $this;
    }

    /**
     * @template T of GameComponent
     * @param class-string<T> $componentClass
     */
    public function removeComponent(string $componentClass): void
    {
        unset($this->components[$componentClass::class]);
    }

    /**
     * @template T of GameComponent
     * @param class-string<T> $componentClass
     */
    public function hasComponent(string $componentClass): bool
    {
        return $this->getComponent($componentClass) !== null;
    }

    public function clone(): GameObject
    {
        return new $this($this->getPrototype(), $this->cloneComponents());
    }

    /** @return GameComponent[] */
    private function cloneComponents(): array
    {
        return array_map(function (GameComponent $component) {
            return clone $component;
        }, $this->components);
    }

    public  function __toString(): string
    {
        return $this::class . '::' . $this->getId();
    }
}
