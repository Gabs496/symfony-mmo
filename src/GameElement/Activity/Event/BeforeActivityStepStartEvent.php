<?php

namespace App\GameElement\Activity\Event;

use App\GameElement\Activity\ActivityInterface;

readonly class BeforeActivityStepStartEvent
{
    public function __construct(
        private ActivityInterface $activity,
        private object            $subject,
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
}