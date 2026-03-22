<?php

namespace App\Engine\Player;

use App\Entity\Data\Player;
use App\GameElement\Character\Event\HealthModifiedEvent;
use App\GameElement\Character\Event\HealthReachedZeroEvent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\Repository\Data\PlayerCharacterRepository;
use App\Stream\PlayerHealthStream;
use App\Stream\Streamer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PlayerHealthEngine implements EventSubscriberInterface
{
    public function __construct(
        private PlayerCharacterRepository $playerCharacterRepository,
        private NotificationEngine        $notificationEngine,
        private Streamer                  $streamer,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            HealthModifiedEvent::class => [
                ['updateHealthBar', 0],
            ],
            HealthReachedZeroEvent::class => [
                ['notifyGameOver', 0],
            ]
        ];
    }

    public function updateHealthBar(HealthModifiedEvent $event): void
    {
        $player = $this->playerCharacterRepository->findOneBy(['gameObject' => $event->getObject()]);
        if (!$player instanceof Player) {
            return;
        }

        $this->streamer->send(new PlayerHealthStream('update', $player));
    }

    public function notifyGameOver(HealthReachedZeroEvent $event): void
    {
        $player = $event->getObject();
        if (!$player instanceof Player) {
            return;
        }

        $this->notificationEngine->danger($player->getId(), 'GAME OVER!');
    }
}