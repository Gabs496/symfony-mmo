<?php

namespace App\GameTask\Message;

readonly class ActivityStepFinish
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