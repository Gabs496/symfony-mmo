<?php

namespace App\Engine\Mob;

use App\Entity\Game\MapSpawnedMob;
use App\GameElement\Health\Event\HealthReachedZeroEvent;
use App\Repository\Game\MapSpawnedMobRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class MobHealthEngine implements EventSubscriberInterface
{

    public function __construct(
        private MapSpawnedMobRepository $mapSpawnedMobRepository,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            HealthReachedZeroEvent::class => [
                ['removeMob', 0],
            ]
        ];
    }

    public function removeMob(HealthReachedZeroEvent $event): void
    {
        $mob = $event->getObject();
        if (!$mob instanceof MapSpawnedMob) {
            return;
        }

        $this->mapSpawnedMobRepository->remove($mob);
    }
}