<?php

namespace App\Twig\Components;

use App\Entity\Data\Player;
use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Repository\InMapRepository;
use App\Repository\Data\PlayerCharacterRepository;
use App\Stream\MapFieldStream;
use App\Stream\Streamer;
use PennyPHP\Core\Event\GameObjectRemoveEvent;
use PennyPHP\Core\Event\GameObjectUpdateEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class MapField
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?string $playerId = null;

    public function __construct(
        private readonly InMapRepository           $inMapRepository,
        private readonly PlayerCharacterRepository $playerCharacterRepository,
        private readonly Streamer                  $streamer,
    )
    {
    }

    public function getPlayer(): Player
    {
        return $this->playerCharacterRepository->find($this->playerId);
    }

    /** @return MapComponent[] */
    public function getInMapComponents(): array
    {
        $player = $this->getPlayer();
        return array_filter($this->inMapRepository->findInMap($player->getMap(), 'field'), function (InMapComponent $inMapComponent) use ($player) {
            return $inMapComponent->getGameObject()->getId() !== $player->getGameObject()->getId();
        });
    }

    #[AsEventListener(GameObjectUpdateEvent::class)]
    public function onUpdate(GameObjectUpdateEvent $event): void
    {
        if ($inMapComponent = $event->getGameObject()->getComponent(InMapComponent::class)) {
            $this->streamer->send(new MapFieldStream($inMapComponent->getMapId(), $event->getGameObject(), 'update'));
        }
    }

    #[AsEventListener(GameObjectRemoveEvent::class)]
    public function orRemove(GameObjectRemoveEvent $event): void
    {
        if ($inMapComponent = $event->getGameObject()->getComponent(InMapComponent::class)) {
            $this->streamer->send(new MapFieldStream($inMapComponent->getMapId(), $event->getGameObject(), 'remove'));
        }
    }
}
