<?php

namespace App\GameElement\Activity\Message;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\ActivitySubjectInterface;

readonly class ActivityTimeout
{
    public function __construct(
        private AbstractActivity         $activity,
        private ActivitySubjectInterface $subject,
    )
    {
    }

    public function getActivity(): AbstractActivity
    {
        return $this->activity;
    }

    public function getSubject(): ActivitySubjectInterface
    {
        return $this->subject;
    }
}