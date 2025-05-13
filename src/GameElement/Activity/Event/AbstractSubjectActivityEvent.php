<?php

namespace App\GameElement\Activity\Event;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\ActivitySubjectInterface;

abstract readonly class AbstractSubjectActivityEvent
{
    public function __construct(
        private AbstractActivity         $activity,
        private ActivitySubjectInterface $subject,
    ) {
    }

    public function getSubject(): ActivitySubjectInterface
    {
        return $this->subject;
    }

    public function getActivity(): AbstractActivity
    {
        return $this->activity;
    }
}