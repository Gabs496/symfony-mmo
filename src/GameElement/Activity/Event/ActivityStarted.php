<?php

namespace App\GameElement\Activity\Event;

readonly class ActivityStarted
{
    public function __construct(
        private string $activityId,
        private object $subject,
    )
    {
    }

    public function getActivityId(): string
    {
        return $this->activityId;
    }

    public function getSubject(): object
    {
        return $this->subject;
    }
}