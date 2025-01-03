<?php

namespace App\GameEngine;

use App\Core\GameObject\AbstractGameObjectCollection;
use App\Core\GameObject\GameObjectCollection;
use App\Core\GameObject\GameObjectReference;
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

                $collection = $this->getCollection($collectionId);
                $objectId = $objectIdProperty->getValue($entity);
                $gameObject = $collection->get($objectId);
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
         // TODO: throw exception
    }
}