<?php

namespace App\GameElement\Activity\Event;

use App\GameElement\Activity\AbstractActivity;

readonly class ActivityEndEvent extends AbstractActivityEvent
{
    public function __construct(
        AbstractActivity $activity,
        private bool $completed = true
    )
    {
        parent::__construct($activity);
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }
}