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
        parent::__construct("Activity " . $this->activity->getId() . " had unexpected stop", 0, $previous);
    }

    public function getActivity(): AbstractActivity
    {
        return $this->activity;
    }
}