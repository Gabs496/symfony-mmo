<?php

namespace App\GameObject\Action;

use App\GameElement\Action\ActionInterface;
use App\GameEngine\Action\ResourceGatheringEngine;
use App\GameEngine\Engine;

#[Engine(ResourceGatheringEngine::class)]
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