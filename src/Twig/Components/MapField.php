<?php

namespace App\Twig\Components;

use App\Entity\Data\Player;
use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Event\MapFieldChangedEvent;
use App\GameElement\Map\Repository\InMapRepository;
use App\Repository\Data\PlayerCharacterRepository;
use App\Stream\Streamer;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class MapField
{
    use DefaultActionTrait;
    #[LiveProp]
    public ?string $playerId = null;

    private ?Player $player = null;

    public function __construct(
        private readonly InMapRepository           $inMapRepository,
        private readonly PlayerCharacterRepository $playerCharacterRepository,
        private readonly Streamer                  $streamer,
    )
    {
    }

    public function getPlayer(): Player
    {
        return $this->player ?: $this->player = $this->playerCharacterRepository->find($this->playerId);
    }

    public function getMapId(): string
    {
        return $this->getPlayer()->getMap();
    }

    public function getChangedEvent(): MapFieldChangedEvent
    {
        return new MapFieldChangedEvent($this->getMapId());
    }

    /** @return MapComponent[] */
    public function getInMapComponents(): array
    {
        $player = $this->getPlayer();
        return array_filter($this->inMapRepository->findInMap($player->getMap(), 'field'), function (InMapComponent $inMapComponent) use ($player) {
            return $inMapComponent->getGameObject()->getId() !== $player->getGameObject()->getId();
        });
    }
}
