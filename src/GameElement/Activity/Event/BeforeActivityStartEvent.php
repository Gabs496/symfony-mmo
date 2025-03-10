<?php

namespace App\GameElement\Activity\Event;

use App\Entity\Data\Activity;
use App\GameElement\Activity\ActivityInterface;

readonly class BeforeActivityStartEvent
{
    public function __construct(
        private ActivityInterface $activity,
        private object            $subject,
        private Activity          $activityEntity,
    )
    {
    }

    public function getActivity(): ActivityInterface
    {
        return $this->activity;
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