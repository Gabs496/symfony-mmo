<?php

namespace App\GameTask;

use App\Entity\Game\MapResource;
use App\GameTask\Message\MapResourceFullfill;
use App\Repository\Game\MapResourceRepository;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('game_task')]
class GameTaskScheduleProvider implements ScheduleProviderInterface
{
    private ?Schedule $schedule = null;

    public function __construct(
        private readonly MapResourceRepository $mapResourceRepository,
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
        /** @var MapResource[] $mapResources */
        $mapResources = $this->mapResourceRepository->findAll();
        foreach ($mapResources as $mapResource) {
            $message = new MapResourceFullfill($mapResource->getId());
            $recurringMessage = RecurringMessage::every($mapResource->getSpotSpawnFrequency() . ' seconds', $message);
            $schedule->add($recurringMessage);
        }
    }
}