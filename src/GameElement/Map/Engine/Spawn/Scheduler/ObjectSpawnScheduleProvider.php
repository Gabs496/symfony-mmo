<?php

namespace App\GameElement\Map\Engine\Spawn\Scheduler;

use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\Map\AbstractMap;
use App\GameElement\Map\Component\Spawn\SpawnComponent;
use App\GameElement\Map\Engine\Spawn\Event\ObjectSpawnAction;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('game_map_spawn')]
class ObjectSpawnScheduleProvider implements ScheduleProviderInterface
{
    private ?Schedule $schedule = null;

    public function __construct(
        private readonly GameObjectEngine $gameObjectEngine,
    )
    {
    }

    public function getSchedule(): Schedule
    {
        if ($this->schedule) {
            return $this->schedule;
        }

        $schedule = new Schedule();
//        $schedule->stateful(new FilesystemAdapter('game_task_scheduler', 0, $this->kernel->getCacheDir() . "/game_environment_tasks"));

        // Tasks
        $this->scheduleObjectSpawn($schedule);

        $this->schedule = $schedule;
        return $schedule;
    }

    private function scheduleObjectSpawn(Schedule $schedule): void
    {
        $maps = $this->gameObjectEngine->getByClass(AbstractMap::class);
        foreach ($maps as $map) {
            $spawn = $map->getComponent(SpawnComponent::getId());
            if (!$spawn) {
                continue;
            }


            $objectSpawns = $spawn->getSpawns();
            foreach ($objectSpawns as $objectSpawn) {
                $message = new ObjectSpawnAction($objectSpawn, $map);
                $recurringMessage = RecurringMessage::every('5 seconds', $message);
                $schedule->add($recurringMessage);
            }
        }
    }
}