<?php

namespace App\GameElement\Activity\Event;

use App\GameElement\Activity\AbstractActivity;

abstract readonly class AbstractActivityEvent
{
    public function __construct(
        private AbstractActivity         $activity,
    ) {
    }

    public function getActivity(): AbstractActivity
    {
        return $this->activity;
    }
}