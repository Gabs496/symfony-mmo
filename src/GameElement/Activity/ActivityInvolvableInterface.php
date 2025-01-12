<?php

namespace App\GameElement\Activity;

interface ActivityInvolvableInterface
{
    public function startActivity(ActivityInterface $activity): void;
    public function endActivity(ActivityInterface $activity): void;
    public function isInvolvedInActivity(?ActivityInterface $activity = null): bool;
    public function getInvolvedActivity(): ?ActivityInterface;
}