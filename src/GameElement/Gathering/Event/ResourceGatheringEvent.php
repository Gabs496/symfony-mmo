<?php

namespace App\GameElement\Gathering\Event;

use App\GameElement\Activity\ActivitySubjectInterface;
use App\GameElement\Gathering\Activity\ResourceGatheringActivity;

readonly class ResourceGatheringEvent
{
    public function __construct(
        private ResourceGatheringActivity         $activity,
        private ActivitySubjectInterface $subject,
    ) {
    }

    public function getSubject(): ActivitySubjectInterface
    {
        return $this->subject;
    }

    public function getActivity(): ResourceGatheringActivity
    {
        return $this->activity;
    }
}