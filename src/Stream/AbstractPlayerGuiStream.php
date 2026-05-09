<?php

namespace App\Stream;

use App\Entity\Data\Player;

abstract readonly class AbstractPlayerGuiStream implements StreamInterface
{
    public function __construct(
        private Player|string $player,
    )
    {
    }

    public function getTopics(): array
    {
        return ['player_gui_' . (is_string($this->player) ? $this->player : $this->player->getId())];
    }

    public function getPlayer(): Player|string
    {
        return $this->player;
    }
}