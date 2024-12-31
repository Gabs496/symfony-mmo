<?php

namespace App\GameObject\Action;

use App\GameElement\Action\ActionInterface;

readonly class ResourceGatheringAction implements ActionInterface
{
    public function __construct(
        private string $playerId,
        private string $mapResourceId,
    )
    {
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function getMapResourceId(): string
    {
        return $this->mapResourceId;
    }
}