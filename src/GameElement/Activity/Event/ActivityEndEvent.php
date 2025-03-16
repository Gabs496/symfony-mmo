<?php

namespace App\GameElement\Activity\Event;

use App\GameElement\Activity\AbstractActivity;

readonly class ActivityEndEvent
{
    public function __construct(
        private AbstractActivity $activity,
        private object           $subject,
    )
    {
    }

    public function getActivity(): AbstractActivity
    {
        return $this->activity;
    }


    public function getSubject(): object
    {
        return $this->subject;
    }
}