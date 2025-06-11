<?php

namespace App\Engine\Player;

use App\Entity\Data\PlayerCharacter;
use App\GameElement\Health\Event\HealthModifiedEvent;
use App\GameElement\Health\Event\HealthReachedZeroEvent;
use App\GameElement\Notification\Engine\NotificationEngine;
use App\Repository\Data\PlayerCharacterRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

class PlayerHealthEngine implements EventSubscriberInterface
{
    public function __construct(
        protected HubInterface $hub,
        protected Environment $twig,
        protected PlayerCharacterRepository $playerCharacterRepository,
        protected NotificationEngine $notificationEngine,
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
        $player = $event->getObject();
        if (!$player instanceof PlayerCharacter) {
            return;
        }

        $this->hub->publish(new Update('player_gui_' . $player->getId(),
            $this->twig->load('streams/player_health.stream.html.twig')->renderBlock('update', ['player' => $player]),
            true
        ));
    }

    public function notifyGameOver(HealthReachedZeroEvent $event): void
    {
        $player = $event->getObject();
        if (!$player instanceof PlayerCharacter) {
            return;
        }

        $this->notificationEngine->danger($player->getId(), 'GAME OVER!');
    }
}