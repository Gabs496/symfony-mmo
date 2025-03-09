<?php

namespace App\GameElement\Activity\Event;

use App\GameElement\Activity\ActivityInterface;

readonly class ActivityStepEndEvent
{
    public function __construct(
        private ActivityInterface $activityTYpe,
        private object            $subject,
    )
    {
    }

    public function getActivityTYpe(): ActivityInterface
    {
        return $this->activityTYpe;
    }


    public function getSubject(): object
    {
        return $this->subject;
    }
}