<?php

namespace App\Engine;

readonly class BroadcastActivityStatusChange
{
    public function __construct(
        private string $activityId,
    )
    {
    }

    public function getActivityId(): string
    {
        return $this->activityId;
    }
}