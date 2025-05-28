<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameObject\Exception\GameObjectNotFound;
use App\GameElement\Core\GameObject\Exception\RegisteredANonGameObjectException;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use ReflectionObject;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @template T
 */
#[AsDoctrineListener(event: Events::postLoad)]
class GameObjectEngine
{
    public function __construct(
        #[AutowireIterator('game.object')]
        protected iterable $gameObjectCollection,
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
        }
    }

    public function get(string $id): GameObjectInterface
    {
        //TODO: try to optimize it
        foreach ($this->gameObjectCollection as $gameObject) {
            if (!$gameObject instanceof GameObjectInterface) {
                throw new RegisteredANonGameObjectException(sprintf('Class %s is tagged as game.object but does not extend %s',$gameObject::class, AbstractGameObject::class));
            }

            if ($gameObject->getId() === $id) {
                return $gameObject;
            }
        }
        throw new GameObjectNotFound('Game object for class "'.$id.'" not found');
    }

    /**
     * @param class-string<T> $class
     * @return T[]
     */
    public function getByClass(string $class): array
    {
        $result = [];
        //TODO: try to optimize it
        foreach ($this->gameObjectCollection as $gameObject) {
            if (!$gameObject instanceof GameObjectInterface) {
                throw new RegisteredANonGameObjectException(sprintf('Class %s is tagged as game.object but does not extend %s',$gameObject::class, AbstractGameObject::class));
            }

            if ($gameObject instanceof $class) {
                $result[] = $gameObject;
            }
        }

        if (empty($result)) {
            throw new GameObjectNotFound('Game object for class "'.$class.'" not found');
        }

        return $result;
    }
}