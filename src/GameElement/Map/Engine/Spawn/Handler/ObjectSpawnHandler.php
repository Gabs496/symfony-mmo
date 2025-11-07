<?php

namespace App\GameElement\Map\Engine\Spawn\Handler;

use App\Entity\Game\GameObject;
use App\Entity\Game\MapObject;
use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Map\AbstractMap;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use App\GameElement\Map\Engine\Spawn\Event\ObjectSpawnAction;
use App\GameElement\Map\Event\Spawn\PreMapObjectSpawn;
use App\Repository\Game\MapObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class ObjectSpawnHandler
{

    public function __construct(
        protected MapObjectRepository $mapObjectRepository,
        protected GameObjectEngine $gameObjectEngine,
        protected EventDispatcherInterface $eventDispatcher,
    )
    {

    }

    public function __invoke(ObjectSpawnAction $event): void
    {
        $map = $event->getMap();
        $object = $event->getObjectSpawn();
        if (!$this->hasFreeSpace($map, $object)) {
            return;
        }

        $randomNumber = bcdiv(random_int(0, 1000000000), 1000000000, 9);
        if (bccomp($randomNumber, $object->getSpawnRate(), 9) !== 1) {
            $this->spawnNewObject($map, $object);
        }
    }

    private function spawnNewObject(AbstractMap $map, ObjectSpawn $objectSpawn): void
    {
        $prototype = $this->gameObjectEngine->getPrototype($objectSpawn->getPrototypeId());
        $instance = (new GameObject($prototype, $prototype->getComponents()));
        $mapObject = new MapObject($map, $instance);
        $this->eventDispatcher->dispatch(new PreMapObjectSpawn($mapObject, $objectSpawn));
        $this->mapObjectRepository->save($mapObject);
    }

    private function hasFreeSpace(AbstractMap $map, ObjectSpawn $objectSpawn): bool
    {

        return $this->getFreeSpace($map, $objectSpawn) > 0;
    }

    private function getFreeSpace(AbstractMap $map, ObjectSpawn $objectSpawn): int
    {
        return $objectSpawn->getMaxAvailability() - $this->getSpaceTaken($map, $objectSpawn);
    }

    private function getSpaceTaken(AbstractMap $map, ObjectSpawn $objectSpawn): int
    {
        $spots = $this->mapObjectRepository->findBy(['mapId' => $map->getId()]);
        $spots = array_filter($spots, fn(MapObject $mapObject) => $mapObject->getGameObject()->getType() === $objectSpawn->getPrototypeId());
        return (new ArrayCollection($spots))->reduce(function (int $carry) {
            return $carry + 1;
        }, 0);
    }
}