<?php

namespace App\Entity\Core;

use App\GameElement\Core\GameComponent\Exception\InvalidGameComponentException;
use App\GameElement\Core\GameComponent\GameComponentInterface;
use App\GameElement\Core\GameObject\AbstractGameObject;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use App\Repository\Game\GameObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: GameObjectRepository::class)]
class GameObject extends AbstractGameObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'guid')]
    protected string $id;

    #[ORM\Column(type: 'json_document')]
    protected array $components;

    #[ORM\Column(name: 'type', type: 'string')]
    protected string $prototype;

    /**
     * @throws InvalidGameComponentException
     */
    public function __construct(GameObjectPrototypeInterface|string $prototype, array $components)
    {
        parent::__construct(Uuid::v7(),$components);
        if ($prototype instanceof GameObjectPrototypeInterface) {
            $prototype = $prototype->getId();
        }
        $this->prototype = $prototype;
    }

    public function getPrototype(): string
    {
        return $this->prototype;
    }

    public function cloneComponent(): void
    {
        $components = $this->getComponents();
        $this->components = [];
        foreach ($components as $component) {
            $this->setComponent(clone $component);
        }
    }

    /** @inheritDoc */
    public function getComponent(string $componentClass): ?GameComponentInterface
    {
        $component = parent::getComponent($componentClass);
        if (!$component) {
            return null;
        }

        $component = clone $component;
        $this->setComponent($component);
        return $component;
    }

    public function isInstanceOf(GameObjectInterface|GameObjectPrototypeInterface $object): bool
    {
        if ($object instanceof GameObjectPrototypeInterface) {
            return $this->getPrototype() === $object->getId();
        }
        return $this->getPrototype() === $object->getPrototype();
    }
}
