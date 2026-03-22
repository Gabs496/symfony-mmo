<?php

namespace App\Twig\Components;

use App\Entity\Data\Player;
use App\GameElement\Map\Component\InMapComponent;
use App\GameElement\Map\Component\MapComponent;
use App\GameElement\Map\Repository\InMapRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class MapField
{
    use DefaultActionTrait;

    public function __construct(
        private readonly InMapRepository $inMapRepository,
        private readonly Security $security,
    )
    {
    }

    public function getPlayer(): Player
    {
        return $this->security->getUser();
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
