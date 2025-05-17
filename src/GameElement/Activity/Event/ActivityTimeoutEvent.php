<?php

namespace App\GameElement\Activity\Event;

use App\GameElement\Activity\Message\ActivityTimeout;

readonly class ActivityTimeoutEvent
{
    public function __construct(
        private ActivityTimeout $timeout,
    )
    {
    }

    public function getTimeout(): ActivityTimeout
    {
        return $this->timeout;
    }
}