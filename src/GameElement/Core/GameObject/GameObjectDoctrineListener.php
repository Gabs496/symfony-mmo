<?php

namespace App\GameElement\Core\GameObject;

use App\GameElement\Core\GameObject\Exception\GameObjectCollectionNotFound;
use App\GameElement\Core\GameObject\Exception\RegisteredANonGameObjectException;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Events;
use ReflectionClass;
use ReflectionObject;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsDoctrineListener(event: Events::postLoad)]
class GameObjectDoctrineListener
{
    public function __construct(
        /** @var AbstractGameObjectCollection[] */
        #[AutowireIterator('game.object_collection')]
        protected iterable $collections,
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
                $collectionId = $gameObjectReference->getClass();
                $objectIdProperty = $reflection->getProperty($gameObjectReference->getObjectIdProperty());
                $objectId = $objectIdProperty->getValue($entity);

                try {
                    $gameObject = $this->findInGameObjectCollection($objectId);
                } catch (GameObjectCollectionNotFound $e) {
                    $collection = $this->getCollection($collectionId);
                    $gameObject = $collection->get($objectId);
                }

                $property->setValue($entity,$gameObject);
            }
        }
    }

    private function getCollection(string $id): AbstractGameObjectCollection
    {
        foreach ($this->collections as $collection) {
            $collectionReflection = new ReflectionClass($collection);
            foreach ($collectionReflection->getAttributes(GameObjectCollection::class) as $collectionAttribute) {
                /** @var GameObjectCollection $gameObjectCollection */
                $gameObjectCollection = $collectionAttribute->newInstance();
                if ($gameObjectCollection->getId() === $id) {
                    return $collection;
                }
            }
        }
         throw new GameObjectCollectionNotFound('Game object collection for class "'.$id.'" not found');
    }

    protected function findInGameObjectCollection(string $id): AbstractGameObject
    {
        foreach ($this->gameObjectCollection as $gameObject) {
            if (!$gameObject instanceof AbstractGameObject) {
                throw new RegisteredANonGameObjectException(sprintf('Class %s is tagged as game.object but does not extend %s',$gameObject::class, AbstractGameObject::class));
            }

            if ($gameObject->getId() === $id) {
                return $gameObject;
            }
        }
        throw new GameObjectCollectionNotFound('Game object for class "'.$id.'" not found');
    }
}