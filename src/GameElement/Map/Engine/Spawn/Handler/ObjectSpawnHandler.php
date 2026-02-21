<?php

namespace App\GameElement\Map\Engine\Spawn\Handler;

use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use App\GameElement\Map\Engine\Spawn\Event\ObjectSpawnAction;
use App\GameElement\Map\Event\Spawn\PreMapObjectSpawn;
use App\GameElement\Position\Component\PlacedComponent;
use App\GameElement\Position\PlacedEngine;
use Doctrine\Common\Collections\ArrayCollection;
use PennyPHP\Core\GameObject\Engine\GameObjectEngine;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class ObjectSpawnHandler
{

    public function __construct(
        protected GameObjectEngine         $gameObjectEngine,
        protected EventDispatcherInterface $eventDispatcher,
        protected PlacedEngine             $placedEngine,
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

    private function spawnNewObject(MapComponent $map, ObjectSpawn $objectSpawn): void
    {
        $instance = $this->gameObjectEngine->make($objectSpawn->getPrototypeId());
        $this->placedEngine->move($instance, "map_field", $map->getId());
        $this->eventDispatcher->dispatch(new PreMapObjectSpawn($map, $objectSpawn, $instance));
    }

    private function hasFreeSpace(MapComponent $map, ObjectSpawn $objectSpawn): bool
    {

        return $this->getFreeSpace($map, $objectSpawn) > 0;
    }

    private function getFreeSpace(MapComponent $map, ObjectSpawn $objectSpawn): int
    {
        return $objectSpawn->getMaxAvailability() - $this->getSpaceTaken($map, $objectSpawn);
    }

    private function getSpaceTaken(MapComponent $map, ObjectSpawn $objectSpawn): int
    {
        $spots = $this->placedEngine->getPlaceds("map_field",$map->getId());
        $spots = array_filter($spots, fn(PlacedComponent $placedComponent) => $placedComponent->getGameObject()->isInstanceOf($objectSpawn->getPrototypeId()));
        return (new ArrayCollection($spots))->reduce(function (int $carry) {
            return $carry + 1;
        }, 0);
    }
}