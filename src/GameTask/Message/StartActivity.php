<?php

namespace App\GameTask\Message;

readonly class StartActivity
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