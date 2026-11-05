<?php

namespace App\GameElement\Activity\Exception;

use App\GameElement\Activity\AbstractActivity;
use RuntimeException;
use Throwable;

class ActivityUnexpectedStopException extends RuntimeException
{
    public function __construct(
        private readonly AbstractActivity $activity,
        ?Throwable                        $previous = null
    )
    {
        $message = "Activity " . $this->activity->getId() . " had unexpected stop";
        if ($previous) {
            $message .= sprintf(': [%s] %s (%s:%d)', $previous::class, $previous->getMessage(), $previous->getFile(), $previous->getLine());
        }
        parent::__construct($message, 0, $previous);
    }

    public function getActivity(): AbstractActivity
    {
        return $this->activity;
    }
}
