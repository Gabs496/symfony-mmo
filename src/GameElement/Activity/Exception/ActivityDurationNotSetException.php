<?php

namespace App\GameElement\Activity\Exception;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Engine\ActivityEngine;
use App\GameElement\Activity\Event\BeforeActivityStartEvent;
use RuntimeException;

class ActivityDurationNotSetException extends RuntimeException
{
    public function __construct(AbstractActivity $activity)
    {
        $message = sprintf("Duration not set for activity %s: it may be set listening event %s", ActivityEngine::getId($activity), BeforeActivityStartEvent::class);
        parent::__construct($message);
    }
}