<?php

namespace App\Stream;

use App\Entity\Data\Player;

abstract readonly class AbstractPlayerGuiStream implements StreamInterface
{
    public function __construct(
        private Player $player,
    )
    {
    }

    public function getTopics(): array
    {
        return ['player_gui_' . $this->player->getId()];
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }
}