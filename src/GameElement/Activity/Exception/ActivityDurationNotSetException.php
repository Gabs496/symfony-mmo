<?php

namespace App\GameElement\Activity\Exception;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Engine\ActivityEngine;
use RuntimeException;

class ActivityDurationNotSetException extends RuntimeException
{
    public function __construct(AbstractActivity $activity)
    {
        $message = sprintf("Duration not set for activity %s", ActivityEngine::getId($activity));
        parent::__construct($message);
    }
}