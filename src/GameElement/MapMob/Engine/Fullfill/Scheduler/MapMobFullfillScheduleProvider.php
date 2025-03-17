<?php

namespace App\GameElement\MapMob\Engine\Fullfill\Scheduler;

use App\GameElement\Core\GameObject\GameObjectEngine;
use App\GameElement\MapMob\Engine\Fullfill\Event\MapMobFullfill;
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
        $this->scheduleMapMobFullfill($schedule);

        $this->schedule = $schedule;
        return $schedule;
    }

    private function scheduleMapMobFullfill(Schedule $schedule): void
    {
        $maps = $this->gameObjectEngine->getByClass(MapWithSpawningMobInterface::class);
        foreach ($maps as $map) {
            $spawningMobs = $map->getSpawningMobs();
            foreach ($spawningMobs as $spawningMob) {
                $message = new MapMobFullfill($spawningMob, $map);
                $recurringMessage = RecurringMessage::every($spawningMob->getSpawnFrequency() . ' seconds', $message);
                $schedule->add($recurringMessage);
            }
        }
    }
}