<?php

namespace App\GameElement\Activity\Event;

use App\Entity\Data\Activity;
use App\GameElement\Activity\ActivityInterface;

readonly class ActivityStartEvent
{
    public function __construct(
        private ActivityInterface $activityType,
        private object            $subject,
        private Activity          $activityEntity,
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

    public function getActivityEntity(): Activity
    {
        return $this->activityEntity;
    }
}