<?php

namespace App\GameElement\Activity\Event;

use App\GameElement\Activity\BaseActivity;

readonly class BeforeActivityStartEvent
{
    public function __construct(
        private BaseActivity $activity,
        private object       $subject,
    )
    {
    }

    public function getActivity(): BaseActivity
    {
        return $this->activity;
    }


    public function getSubject(): object
    {
        return $this->subject;
    }
}