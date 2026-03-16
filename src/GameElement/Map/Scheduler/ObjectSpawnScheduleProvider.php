<?php

namespace App\GameElement\Map\Scheduler;

use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Message\ObjectSpawnAction;
use Doctrine\ORM\EntityManagerInterface;
use PennyPHP\Core\InMemoryGameObjectInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('game_map_spawn')]
class ObjectSpawnScheduleProvider implements ScheduleProviderInterface
{
    private ?Schedule $schedule = null;
    private array $inMemoryMaps;

    /** @param iterable<InMemoryGameObjectInterface> $inMemoryGameObjects */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        #[AutowireIterator('game.object.in_memory')]
        iterable               $inMemoryGameObjects,
    )
    {
        $this->inMemoryMaps = [];
        foreach ($inMemoryGameObjects as $inMemoryGameObject) {
            if ($mapComponent = $inMemoryGameObject->getComponent(MapComponent::class)) {
                $this->inMemoryMaps[] = $mapComponent;
            }
        }
    }

    public function getSchedule(): Schedule
    {
        if ($this->schedule) {
            return $this->schedule;
        }

        $schedule = new Schedule();

        // Tasks
        $this->scheduleObjectSpawn($schedule);

        $this->schedule = $schedule;
        return $schedule;
    }

    private function scheduleObjectSpawn(Schedule $schedule): void
    {
        $maps = $this->entityManager->getRepository(MapComponent::class)->findAll();
        $maps += $this->inMemoryMaps;
        foreach ($maps as $map) {
            $objectSpawns = $map->getSpawns();
            if (empty($objectSpawns)) {
                continue;
            }


            foreach ($objectSpawns as $objectSpawn) {
                $message = new ObjectSpawnAction($objectSpawn, $map);
                $recurringMessage = RecurringMessage::every('5 seconds', $message);
                $schedule->add($recurringMessage);
            }
        }
    }
}