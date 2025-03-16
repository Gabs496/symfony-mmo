<?php

namespace App\GameElement\MapResource\Engine\Fullfill\Scheduler;

use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\MapResource\Engine\Fullfill\Event\MapResourceFullfill;
use App\GameElement\MapResource\MapWithSpawningResourceInterface;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('game_map_resource_fullfill')]
class MapResourceFullfillScheduleProvider implements ScheduleProviderInterface
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
        $this->scheduleMapResourceFullfill($schedule);

        $this->schedule = $schedule;
        return $schedule;
    }

    private function scheduleMapResourceFullfill(Schedule $schedule): void
    {
        $maps = $this->gameObjectEngine->getByClass(MapWithSpawningResourceInterface::class);
        foreach ($maps as $map) {
            $spawningResources = $map->getSpawningResources();
            foreach ($spawningResources as $spawningResource) {
                $message = new MapResourceFullfill($spawningResource, $map);
                $recurringMessage = RecurringMessage::every($spawningResource->getSpotSpawnFrequency() . ' seconds', $message);
                $schedule->add($recurringMessage);
            }
        }
    }
}