<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameObject\Exception\GameObjectNotFound;
use App\GameElement\Core\GameObject\Exception\RegisteredANonGameObjectException;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use ReflectionObject;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @template T
 */
#[AsDoctrineListener(event: Events::postLoad)]
class GameObjectEngine
{
    public function __construct(
        #[AutowireIterator('game.object')]
        protected iterable $gameObjectCollection,
        #[AutowireIterator('game.object.prototype')]
        protected iterable $gameObjectPrototypeCollection,
        protected CacheInterface $gameObjectCache,
    )
    {
    }

    public function postLoad(PostLoadEventArgs $args): void
    {

        $entity = $args->getObject();
        $reflection = new ReflectionObject($entity);
        foreach ($reflection->getProperties() as $property) {
            foreach ($property->getAttributes(GameObjectReference::class) as $gameObjectAttribute) {
                /** @var GameObjectReference $gameObjectReference */
                $gameObjectReference = $gameObjectAttribute->newInstance();
                $objectIdProperty = $reflection->getProperty($gameObjectReference->getObjectIdProperty());
                $objectId = $objectIdProperty->getValue($entity);

                $gameObject = $this->get($objectId);
                $property->setValue($entity,$gameObject);
            }
            foreach ($property->getAttributes(GameObjectPrototypeReference::class) as $gameObjectPrototypeAttribute) {
                /** @var GameObjectPrototypeReference $gameObjectPrototypeReference */
                $gameObjectPrototypeReference = $gameObjectPrototypeAttribute->newInstance();
                $objectPrototypeIdProperty = $reflection->getProperty($gameObjectPrototypeReference->getObjectPrototypeIdProperty());
                $objectId = $objectPrototypeIdProperty->getValue($entity);

                $prototype = $this->getPrototype($objectId);
                $property->setValue($entity,$prototype);
            }
        }
    }

    public function get(string $id): GameObjectInterface
    {
        return $this->gameObjectCache->get($id,function (ItemInterface $item) use ($id) {
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
        return $this->gameObjectCache->get('prototype.' . $id,function (ItemInterface $item) use ($id) {
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
     */
    public function getByClass(string $class): array
    {
        return $this->gameObjectCache->get(hash('md5', $class),function (ItemInterface $item) use ($class) {
            $result = [];
            //TODO: try to optimize it
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