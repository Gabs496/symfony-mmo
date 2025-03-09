<?php

namespace App\Engine\Player\Event;

readonly class PlayerBackpackUpdateEvent
{
    public function __construct(
        private string $playerId,
    )
    {
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }
}