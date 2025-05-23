<?php

namespace App\GameElement\MapMob\Engine\Spawn\Scheduler;

use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\MapMob\Engine\Spawn\Event\MapMobSpawnAction;
use App\GameElement\MapMob\MapWithSpawningMobInterface;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('game_map_mob_fullfill')]
class MapMobFullfillScheduleProvider implements ScheduleProviderInterface
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
        $this->scheduleMapMobSpawn($schedule);

        $this->schedule = $schedule;
        return $schedule;
    }

    private function scheduleMapMobSpawn(Schedule $schedule): void
    {
        $maps = $this->gameObjectEngine->getByClass(MapWithSpawningMobInterface::class);
        foreach ($maps as $map) {
            $spawningMobs = $map->getSpawningMobs();
            foreach ($spawningMobs as $spawningMob) {
                $message = new MapMobSpawnAction($spawningMob, $map);
                $recurringMessage = RecurringMessage::every('5 seconds', $message);
                $schedule->add($recurringMessage);
            }
        }
    }
}