<?php

namespace App\Engine\Player;

use App\Engine\PlayerCharacterManager;
use App\Entity\Data\PlayerCharacter;
use App\GameElement\Health\Event\HealthDecreasedEvent;
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

    public static function getSubscribedEvents()
    {
        return [
            HealthDecreasedEvent::class => [
                ['saveNewHealth', 0],
                ['updateHealthBar', 0],
            ],
            HealthReachedZeroEvent::class => [
                ['notifyGameOver', 0],
            ]
        ];
    }

    public function saveNewHealth(HealthDecreasedEvent $event): void
    {
        $health = $event->getHealthComponent();
        $player = $health->getGameObject();
        if (!$player instanceof PlayerCharacterManager) {
            return;
        }

        $player = $this->playerCharacterRepository->find($player->getId());
        if (!$player instanceof PlayerCharacter) {
            return;
        }

        $player->setCurrentHealth($health->getCurrentHealth());
        $this->playerCharacterRepository->save($player);
    }

    public function updateHealthBar(HealthDecreasedEvent $event): void
    {
        $health = $event->getHealthComponent();
        $player = $health->getGameObject();
        if (!$player instanceof PlayerCharacterManager) {
            return;
        }

        $this->hub->publish(new Update('player_gui_' . $player->getId(),
            $this->twig->load('parts/player_health.stream.html.twig')->renderBlock('update', ['player_id' => $player->getId(), 'health' => $health]),
            true
        ));
    }

    public function notifyGameOver(HealthReachedZeroEvent $event): void
    {
        $health = $event->getHealthComponent();
        $player = $health->getGameObject();
        if (!$player instanceof PlayerCharacterManager) {
            return;
        }

        $this->notificationEngine->danger($player->getId(), 'GAME OVER!');
    }
}