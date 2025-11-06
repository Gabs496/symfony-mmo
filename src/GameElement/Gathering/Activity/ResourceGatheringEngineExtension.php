<?php

namespace App\GameElement\Gathering\Activity;

use App\GameElement\Activity\AbstractActivity;
use App\GameElement\Activity\Engine\ActivityEngineExtensionInterface;
use App\GameElement\Core\GameObject\GameObjectInterface;
use App\GameElement\Core\Token\TokenEngine;
use App\GameElement\Gathering\Engine\GatheringEngine;

/** @extends ActivityEngineExtensionInterface<ResourceGatheringActivity> */
readonly class ResourceGatheringEngineExtension implements ActivityEngineExtensionInterface
{
    public function __construct(
        private TokenEngine $tokenEngine,
        private GatheringEngine  $gatheringEngine,
    )
    {
    }

    public function supports(AbstractActivity $activity): bool
    {
        return $activity instanceof ResourceGatheringActivity;
    }

    public function getDuration(AbstractActivity $activity): float
    {
        return $activity->getGathering()->getGatheringTime();
    }

    public function beforeStart(AbstractActivity $activity): void
    {
        return;
    }

    public function onComplete(AbstractActivity $activity): void
    {
        /** @var GameObjectInterface $gameObject */
        $gameObject = $this->tokenEngine->exchange($activity->getResourceToken());
        $this->gatheringEngine->gather($activity->getSubject(), $gameObject);
    }

    public function onFinish(AbstractActivity $activity): void
    {
    }

    public function cancel(AbstractActivity $activity): void
    {
        // TODO: Implement cancel() method.
    }
}