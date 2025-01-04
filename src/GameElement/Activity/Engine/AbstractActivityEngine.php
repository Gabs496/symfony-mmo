<?php

namespace App\GameElement\Activity\Engine;

use App\Entity\ActivityStep;

/**
 * @template T
 * @template S
 */
readonly abstract class AbstractActivityEngine
{
    /**
     * @psalm-param T $subject
     * @psalm-param S $directObject
     */
    public abstract function run(object $subject, object $directObject);

    /**
     * @psalm-param T $subject
     * @psalm-param S $directObject
     * @return ActivityStep[]
     */
    public abstract function generateSteps(object $subject, object $directObject): iterable;

    /**
     * @psalm-param T $subject
     * @psalm-param S $directObject
     */
    public abstract function onStepFinish(object $subject, object $directObject, ActivityStep $step): void;

    protected function waitForStepFinish(ActivityStep $step): void
    {
        $seconds = floor($step->getDuration());
        $microseconds = (int)bcmul(bcsub($step->getDuration(), $seconds, 4), 1000000, 0);
        sleep($seconds);
        usleep($microseconds);
    }
}