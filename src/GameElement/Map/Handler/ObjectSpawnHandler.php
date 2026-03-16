<?php

namespace App\GameElement\Map\Handler;

use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Component\Spawn\ObjectSpawn;
use App\GameElement\Map\Engine\MapSpawnEngine;
use App\GameElement\Map\Event\PreMapObjectSpawnEvent;
use App\GameElement\Map\Message\ObjectSpawnAction;
use App\GameElement\Map\Repository\InMapRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Twig\Environment;

#[AsMessageHandler]
readonly class ObjectSpawnHandler
{
    public function __construct(
        private InMapRepository $inMapRepository,
        private MapSpawnEngine  $mapSpawnEngine,
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $entityManager,
        private HubInterface $hub,
        private Environment $twig,
    )
    {

    }

    public function __invoke(ObjectSpawnAction $event): void
    {
        $map = $event->getMap();
        $objectSpawn = $event->getObjectSpawn();

        if (!$this->hasFreeSpace($map, $objectSpawn)) {
            return;
        }

        if ($this->isTimeToSpawn($objectSpawn->getSpawnRate())) {
            $gameObject = $this->mapSpawnEngine->spawnNewObject($map, $objectSpawn);
            $this->eventDispatcher->dispatch(new PreMapObjectSpawnEvent($map, $objectSpawn, $gameObject));
            $this->entityManager->persist($gameObject);
            $this->entityManager->flush();
            $this->hub->publish(new Update(
                'map_objects_' . $map->getGameObject()->getId(),
                $this->twig->load('map/field.stream.html.twig')->renderBlock('create', ['id' => $gameObject->getId(), 'entity' => $gameObject])
            ));
        }
    }

    private function isTimeToSpawn(float $spawnRate): bool
    {
        $randomNumber = bcdiv(random_int(0, 1000000000), 1000000000, 9);
        return bccomp($randomNumber, $spawnRate, 9) !== 1;
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
        $spots = $this->inMapRepository->findInMap($map, 'field');
        $spots = array_filter($spots, fn(InMapComponent $inMapComponent) => $inMapComponent->getGameObject()->isInstanceOf($objectSpawn->getPrototypeId()));
        return count($spots);
    }
}