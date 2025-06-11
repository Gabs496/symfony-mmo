<?php

namespace App\GameElement\Activity\Engine;

use App\GameElement\Activity\AbstractActivity;

/**
 * @template S of AbstractActivity
 */
interface ActivityEngineExtensionInterface
{
    public function supports(AbstractActivity $activity): bool;

    /**
     * In seconds
     * @param S $activity
     */
    public function getDuration(AbstractActivity $activity): float;

    /** @param S $activity */
    public function beforeStart(AbstractActivity $activity): void;

    /** @param S $activity */
    public function onComplete(AbstractActivity $activity): void;

    /** @param S $activity */
    public function onFinish(AbstractActivity $activity): void;

    /** @param S $activity */
    public function cancel(AbstractActivity $activity): void;
}