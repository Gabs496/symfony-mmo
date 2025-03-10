<?php

namespace App\GameElement\Activity\Event;

use App\GameElement\Activity\ActivityInterface;

readonly class BeforeActivityStepStartEvent
{
    public function __construct(
        private ActivityInterface $activityType,
        private object            $subject,
    )
    {
    }

    public function getActivityType(): ActivityInterface
    {
        return $this->activityType;
    }


    public function getSubject(): object
    {
        return $this->subject;
    }
}