<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\ActivityInterface;

#[Activity(id: 'RESOURCE_GATHERING')]
readonly class ResourceGatheringActivity implements ActivityInterface
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