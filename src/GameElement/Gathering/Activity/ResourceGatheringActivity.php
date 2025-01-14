<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\Activity;
use App\GameElement\Activity\ActivityInterface;
use App\GameElement\Gathering\AbstractResource;

#[Activity(id: 'RESOURCE_GATHERING')]
readonly class ResourceGatheringActivity implements ActivityInterface
{
    public function __construct(
        private AbstractResource $resource,
    )
    {
    }

    public function getResource(): AbstractResource
    {
        return $this->resource;
    }
}