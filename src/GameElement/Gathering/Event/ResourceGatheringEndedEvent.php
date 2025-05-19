<?php

namespace App\GameElement\Gathering\Event;

use App\GameElement\Gathering\Activity\ResourceGatheringActivity;

readonly class ResourceGatheringEndedEvent
{
    public function __construct(
        private ResourceGatheringActivity         $activity,
    ) {
    }

    public function getActivity(): ResourceGatheringActivity
    {
        return $this->activity;
    }
}