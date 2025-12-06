<?php

namespace App\GameElement\Core\GameObject\Engine;

use App\GameElement\Core\GameObject\Attribute\GameObjectPrototypeReference;
use App\GameElement\Core\GameObject\Doctrine\Type\GameObjectPlaceholder;
use App\GameElement\Core\GameObject\Exception\GameObjectNotFound;
use App\GameElement\Core\GameObject\Exception\RegisteredANonGameObjectException;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\GameObjectPrototype\Doctrine\Type\GameObjectPrototypePlaceholder;
use App\GameElement\Core\GameObjectPrototype\GameObjectPrototypeInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use Psr\Cache\InvalidArgumentException;
use ReflectionObject;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @template T
 */
#[AsDoctrineListener(event: Events::postLoad)]
readonly class GameObjectEngine
{
    public function __construct(
        #[AutowireIterator('game.object')]
        private iterable       $gameObjectCollection,
        #[AutowireIterator('game.object.prototype')]
        private iterable       $gameObjectPrototypeCollection,
        private CacheInterface $gameObjectCache,
    )
    {
    }

    public function postLoad(PostLoadEventArgs $args): void
    {
        $entity = $args->getObject();

        $reflection = new ReflectionObject($entity);

        foreach ($reflection->getProperties() as $property) {
            $type = $property->getType();
            if ($type->getName() === GameObjectInterface::class) {
                $field = $property->getName();
                $property = $reflection->getProperty($field);
                if (!$property->isInitialized($entity)) {
                    break;
                }
                $value = $property->getValue($entity);
                if ($value instanceof GameObjectPlaceholder) {
                    $gameObject = $this->get($value->getId());
                    $property->setValue($entity,$gameObject);
                }
            }

            if ($type->getName() === GameObjectPrototypeInterface::class) {
                $field = $property->getName();
                $property = $reflection->getProperty($field);
                if (!$property->isInitialized($entity)) {
                    break;
                }
                $value = $property->getValue($entity);
                if ($value instanceof GameObjectPrototypePlaceholder) {
                    $prototype = $this->getPrototype($value->getPrototypeId());
                    $property->setValue($entity,$prototype);
                }
            }
        }
    }

    public function get(string $id): GameObjectInterface
    {
        return $this->gameObjectCache->get('game_object.' . $id,function (ItemInterface $item) use ($id) {
            foreach ($this->gameObjectCollection as $gameObject) {
                if (!$gameObject instanceof GameObjectInterface) {
                    throw new RegisteredANonGameObjectException(sprintf('Class %s is tagged as game.object but does not extend %s',$gameObject::class, GameObjectInterface::class));
                }

                if ($gameObject->getId() === $id) {
                    return $gameObject;
                }
            }
            throw new GameObjectNotFound('Game object for class "'.$id.'" not found');
        });
    }

    public function getPrototype(string $id): GameObjectPrototypeInterface
    {
        return $this->gameObjectCache->get('game_object_prototype.' . $id,function (ItemInterface $item) use ($id) {
            foreach ($this->gameObjectPrototypeCollection as $gameObjectPrototype) {
                if (!$gameObjectPrototype instanceof GameObjectPrototypeInterface) {
                    throw new RegisteredANonGameObjectException(sprintf('Class %s is tagged as game.object but does not extend %s',$gameObjectPrototype::class, GameObjectPrototypeInterface::class));
                }

                if ($gameObjectPrototype->getid() === $id) {
                    return $gameObjectPrototype;
                }
            }
            throw new GameObjectNotFound('Game object prototype for class "'.$id.'" not found');
        });
    }

    /**
     * @param class-string<T> $class
     * @return T[]
     * @throws InvalidArgumentException
     */
    public function getByClass(string $class): array
    {
        return $this->gameObjectCache->get(hash('md5', $class),function (ItemInterface $item) use ($class) {
            $result = [];
            foreach ($this->gameObjectCollection as $gameObject) {
                if (!$gameObject instanceof GameObjectInterface) {
                    throw new RegisteredANonGameObjectException(sprintf('Class %s is tagged as game.object but does not extend %s',$gameObject::class, GameObjectInterface::class));
                }

                if ($gameObject instanceof $class) {
                    $result[] = $gameObject;
                }
            }

            if (empty($result)) {
                throw new GameObjectNotFound('Game object for class "'.$class.'" not found');
            }

            return $result;
        });
    }
}