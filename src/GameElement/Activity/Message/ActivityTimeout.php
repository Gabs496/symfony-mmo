<?php

namespace App\GameElement\Activity\Message;

use App\GameElement\Activity\AbstractActivity;

readonly class ActivityTimeout
{
    public function __construct(
        private AbstractActivity $activity,
        private mixed $subjectId,
    )
    {
    }

    public function getActivity(): AbstractActivity
    {
        return $this->activity;
    }

    public function getSubjectId(): mixed
    {
        return $this->subjectId;
    }
}